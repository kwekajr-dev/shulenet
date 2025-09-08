<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\InvoiceIssued;
use App\Notifications\PaymentReceived;

class InvoiceController extends Controller
{
    public function index()
    {
        if (Auth::user()->type === 'parent') {
            $students = Student::with(['invoices' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
                ->where('parent_id', Auth::id())
                ->get();
                
            $invoices = Invoice::with(['student'])
                ->whereHas('student', function($query) {
                    $query->where('parent_id', Auth::id());
                })
                ->get();
        } else {
            $students = Student::with(['invoices' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])->get();
            $invoices = Invoice::with(['student'])->get();
        }
        
        return view('invoices.index', compact('students', 'invoices'));
    }

    
    public function show($invoiceId)
    {
        try {
            $invoice = Invoice::with(['student', 'student.parent', 'payments'])->findOrFail($invoiceId);
            
            if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
        
            $html = view('invoices.show-modal', compact('invoice'))->render();
            return response()->json(['html' => $html]);
            
        } catch (\Exception $e) {
            Log::error('Error showing invoice: ' . $e->getMessage());
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    }
    
   public function store(Request $request)
{
    try {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today'
        ]);

        DB::beginTransaction();

        $invoice = Invoice::create([
            'student_id' => $request->student_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'status' => 'pending'
        ]);
        
        // Send notification to parent
        try {
            if ($invoice->student && $invoice->student->parent) {
                $invoice->student->parent->notify(new InvoiceIssued($invoice));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
        }

        DB::commit();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'invoice' => $invoice
            ]);
        }

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        $errors = $e->errors();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }
        
        return back()->withErrors($errors)->withInput();
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating invoice: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'Failed to create invoice: ' . $e->getMessage())->withInput();
    }
}

    
   
    // Fixed notifyParent method (this method name should match your route)
    public function notifyParent($invoiceId)
    {
        try {
            Log::info('Notification request received for invoice: ' . $invoiceId);
            
            $invoice = Invoice::with('student.parent')->findOrFail($invoiceId);
            Log::info('Invoice found: ' . $invoice->id);
            
            if (!$invoice->student) {
                Log::warning('No student found for invoice: ' . $invoice->id);
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }
            
            $parent = $invoice->student->parent;
            if (!$parent) {
                Log::warning('No parent found for student: ' . $invoice->student->id);
                return response()->json(['success' => false, 'message' => 'Parent not found'], 404);
            }
            
            Log::info('Parent found: ' . $parent->email);
            
            // Send notification to parent
            $parent->notify(new InvoiceIssued($invoice));
            
            Log::info('Notification processed successfully for: ' . $parent->email);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully to ' . $parent->email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Full notification error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Invoice $invoice)
    {
        try {
            if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            // Load relationships
            $invoice->load(['student', 'student.parent', 'payments']);
            
            // TODO: Implement actual PDF generation
            // For now, just return success message
            return response()->json([
                'success' => true,
                'message' => 'PDF download functionality will be implemented soon.',
                'download_url' => '#'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating download: ' . $e->getMessage()
            ], 500);
        }
    }

    // Other existing methods remain the same...
    public function showPaymentForm($invoiceId = null)
    {
        if (!$invoiceId) {
            return redirect()->route('payments.index')
                ->with('info', 'Please select an invoice to make a payment.');
        }
        
        $invoice = Invoice::with(['student', 'student.parent', 'payments'])->findOrFail($invoiceId);
        
        if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($invoice->status === 'paid') {
            return redirect()->route('payments.index')
                ->with('info', 'This invoice has already been paid.');
        }
        
        return view('payments.payment-form', compact('invoice'));
    }



    public function confirmPayment(Request $request, $invoiceId)
{
    try {
        // Find the invoice first
        $invoice = Invoice::with('student')->findOrFail($invoiceId);
        
        // Check authorization for parents
        if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        // Check if already paid
        if ($invoice->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice is already paid'
            ], 400);
        }
        
        // Validate only the payment confirmation fields - NOT student_id
        $request->validate([
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        // Create payment record
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount,
            'method' => $request->payment_method,
            'transaction_id' => $request->transaction_reference ?? $this->generateTransactionId(),
            'paid_at' => now(),
            'status' => 'completed',
            'notes' => $request->notes
        ]);
        
        // Update invoice status to paid
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        
        DB::commit();
        
        // Send notification to parent
        try {
            if ($invoice->student && $invoice->student->parent) {
                $invoice->student->parent->notify(new PaymentReceived($invoice, $payment));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send payment notification: ' . $e->getMessage());
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully'
            ]);
        }
        
        return redirect()->route('invoices.index')
            ->with('success', 'Payment confirmed successfully!');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Payment confirmation error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment confirmation failed: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Payment confirmation failed: ' . $e->getMessage());
    }
}



    public function showPaymentConfirmation($invoiceId)
{
    try {
        $invoice = Invoice::with('student')->findOrFail($invoiceId);
        
        // Authorization check
        if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('payments.confirmation', compact('invoice'));
        
    } catch (\Exception $e) {
        Log::error('Error loading payment confirmation: ' . $e->getMessage());
        return view('payments.confirmation', ['invoice' => null]);
    }
}

    public function processPayment(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        
        if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoiceId)
                ->with('info', 'This invoice has already been paid.');
        }
        
        $request->validate([
            'payment_method' => 'required|in:card,bank_transfer,paypal',
            'transaction_reference' => 'required_if:payment_method,bank_transfer',
        ]);
        
        try {
            DB::beginTransaction();
            
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'method' => $request->payment_method,
                'transaction_id' => $request->transaction_reference ?? $this->generateTransactionId(),
                'paid_at' => now(),
                'status' => 'completed',
            ]);
            
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            
            $invoice->student->parent->notify(new PaymentReceived($invoice, $payment));
            
            DB::commit();
            
            return redirect()->route('invoices.show', $invoiceId)
                ->with('success', 'Payment processed successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function paymentHistory($invoiceId)
    {
        $invoice = Invoice::with('payments')->findOrFail($invoiceId);
        
        if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('invoices.payment-history', compact('invoice'));
    }

    public function paymentsIndex()
    {
        if (Auth::user()->type === 'parent') {
            $students = Student::with(['invoices' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
                ->where('parent_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
                
            $invoices = Invoice::with(['student'])
                ->whereHas('student', function($query) {
                    $query->where('parent_id', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $students = Student::with(['invoices' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])->orderBy('created_at', 'desc')->get();
            $invoices = Invoice::with(['student'])->orderBy('created_at', 'desc')->get();
        }

        return view('payments.index', compact('students', 'invoices'));
    }

    private function generateTransactionId()
    {
        return 'TXN' . time() . rand(1000, 9999);
    }
}