@extends('layouts.app')

@section('title', 'Payment Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Payment Management</h1>
            <p class="text-muted">Student payments and invoices overview</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                <i class="fas fa-plus"></i> Create Invoice
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $invoices->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $invoices->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $invoices->filter(function($invoice) { 
                                    return $invoice->status === 'pending' && $invoice->due_date < now(); 
                                })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students with Payments -->
    @foreach($students as $student)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $student->name }} - Payment Overview</h6>
            <span class="badge badge-info">Total Invoices: {{ $student->invoices->count() }}</span>
        </div>
        <div class="card-body">
            @if($student->invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->invoices as $invoice)
                        <tr>
                            <td>#{{ $invoice->id }}</td>
                            <td>{{ $invoice->title }}</td>
                            <td>{{ number_format($invoice->amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                            <td>
                                @if($invoice->status === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($invoice->status === 'pending')
                                    @if($invoice->due_date < now())
                                        <span class="badge badge-danger">Overdue</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($invoice->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-info btn-sm" onclick="viewInvoice({{ $invoice->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($invoice->status === 'pending')
                                        <button type="button" class="btn btn-success btn-sm" onclick="confirmPayment({{ $invoice->id }})" title="Confirm Payment">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm" onclick="sendNotification({{ $invoice->id }})" title="Send Notification">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="downloadInvoice({{ $invoice->id }})" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="2" class="text-right">Total:</td>
                            <td>{{ number_format($student->invoices->sum('amount'), 2) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No invoices found for this student.
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceModalLabel">Create New Invoice</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createInvoiceForm" action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Payment Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="e.g., School Fees, Uniform, Books" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">TZS</span>
                            </div>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Additional details about this payment"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Invoice Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewInvoiceModalLabel">Invoice Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="invoiceDetailsContent">
                <!-- Invoice details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" role="dialog" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmPaymentModalLabel">Confirm Payment</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="confirmPaymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Are you sure you want to mark this invoice as paid?
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transaction_reference">Transaction Reference</label>
                        <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" placeholder="Optional reference number">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for each student table if needed
    $('table').each(function() {
        if ($(this).find('tbody tr').length > 0) {
            $(this).DataTable({
                "pageLength": 10,
                "order": [[ 0, "desc" ]]
            });
        }
    });
});

function viewInvoice(invoiceId) {
    $.ajax({
        url: `/invoices/${invoiceId}`,
        method: 'GET',
        success: function(response) {
            $('#invoiceDetailsContent').html(response);
            $('#viewInvoiceModal').modal('show');
        },
        error: function(xhr) {
            alert('Error loading invoice details');
        }
    });
}

function confirmPayment(invoiceId) {
    $('#confirmPaymentForm').attr('action', `/invoices/${invoiceId}/confirm-payment`);
    $('#confirmPaymentModal').modal('show');
}

function sendNotification(invoiceId) {
    if (confirm('Send payment reminder notification to parent?')) {
        $.ajax({
            url: `/invoices/${invoiceId}/notify`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Notification sent successfully!');
            },
            error: function(xhr) {
                alert('Error sending notification');
            }
        });
    }
}

function downloadInvoice(invoiceId) {
    window.open(`/invoices/${invoiceId}/download`, '_blank');
}

// Form submission handlers
$('#createInvoiceForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            $('#createInvoiceModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            let errorMessage = 'Please fix the following errors:\n';
            Object.keys(errors).forEach(function(key) {
                errorMessage += '- ' + errors[key][0] + '\n';
            });
            alert(errorMessage);
        }
    });
});

$('#confirmPaymentForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            $('#confirmPaymentModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert('Error confirming payment');
        }
    });
});
</script>
@endsection