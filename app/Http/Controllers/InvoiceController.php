<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\InvoiceIssued;
use App\Notifications\PaymentReceived;

class InvoiceController extends Controller
{
    public function index()
    {
        if (Auth::user()->type === 'parent') {
            $students = Student::with(['invoices'])
                ->where('parent_id', Auth::id())
                ->get();
                
            $invoices = Invoice::whereHas('student', function($query) {
                $query->where('parent_id', Auth::id());
            })->get();
        } else {
            $students = Student::with(['invoices'])->get();
            $invoices = Invoice::all();
        }
        
        return view('invoices.index', compact('students', 'invoices'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date'
        ]);

        $invoice = Invoice::create([
            'student_id' => $request->student_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'status' => 'pending' // Set default status to pending
        ]);
        
        // Send notification to parent
        $invoice->student->parent->notify(new InvoiceIssued($invoice));

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

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
            
            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'method' => $request->payment_method,
                'transaction_id' => $request->transaction_reference ?? $this->generateTransactionId(),
                'paid_at' => now(),
                'status' => 'completed',
            ]);
            
            // Update invoice status to paid
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            
            // Send notification to parent
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

    // New method to confirm payment (admin action)
    public function confirmPayment(Request $request, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        
        $request->validate([
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque',
            'transaction_reference' => 'nullable|string|max:255',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'method' => $request->payment_method,
                'transaction_id' => $request->transaction_reference ?? $this->generateTransactionId(),
                'paid_at' => now(),
                'status' => 'completed',
            ]);
            
            // Update invoice status to paid
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('invoices.index')
                ->with('success', 'Payment confirmed successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Payment confirmation failed: ' . $e->getMessage())
                ->withInput();
        }
    }

   
    // method to send notification
public function sendNotification($invoiceId)
{
    $invoice = Invoice::with('student.parent')->findOrFail($invoiceId);
    
    try {
        // Send notification to parent
        $invoice->student->parent->notify(new InvoiceIssued($invoice));
        
        return redirect()->route('invoices.index')
            ->with('success', 'Notification sent successfully!');
            
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Notification error: ' . $e->getMessage());
        
        // Alternative approach - just send email without database notification
        try {
            $invoice->student->parent->notify(new InvoiceIssued($invoice));
            
            return redirect()->route('invoices.index')
                ->with('success', 'Email notification sent successfully!');
        } catch (\Exception $emailException) {
            return redirect()->back()
                ->with('error', 'Failed to send notification: ' . $emailException->getMessage());
        }
    }
}

    private function generateTransactionId()
    {
        return 'TXN' . time() . rand(1000, 9999);
    }

    public function download($invoiceId)
    {
        $invoice = Invoice::with(['student', 'student.parent', 'payments'])->findOrFail($invoiceId);
        
        if (Auth::user()->type === 'parent' && $invoice->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return redirect()->route('invoices.show', $invoiceId)
            ->with('info', 'PDF download functionality will be implemented soon.');
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
            $students = Student::with(['invoices'])
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
            $students = Student::with(['invoices'])->orderBy('created_at', 'desc')->get();
            $invoices = Invoice::with(['student'])->orderBy('created_at', 'desc')->get();
        }

        return view('payments.index', compact('students', 'invoices'));
    }
}