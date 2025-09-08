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
                            <td>{{ $invoice->status }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm view-invoice-btn" 
                                            data-invoice-id="{{ $invoice->id }}" title="View">
                                        <i class="fas fa-eye"></i> View
                                    </button>

                                    @if($invoice->status === 'pending')
                                    <a href="{{ route('invoices.confirmation-page', $invoice->id) }}" 
                                       class="btn btn-primary confirm-payment-btn" 
                                       title="Confirm Payment">
                                        <i class="fas fa-check"></i> Confirm
                                    </a>
                                    @endif
                                   
                                    <button type="button" class="btn btn-secondary btn-sm download-btn" 
                                            data-invoice-id="{{ $invoice->id }}" title="Download">
                                        <i class="fas fa-download"></i> Download
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initial attachment of button handlers
    attachButtonHandlers();
    
    // SEPARATE form submission handlers
    $('#createInvoiceForm').on('submit', function(e) {
        e.preventDefault();
        handleInvoiceFormSubmission($(this));
    });
    
    // View invoice button handler
    $('.view-invoice-btn').on('click', function() {
        const invoiceId = $(this).data('invoice-id');
        loadInvoiceDetails(invoiceId);
    });
    
    // Download invoice button handler
    $('.download-btn').on('click', function() {
        const invoiceId = $(this).data('invoice-id');
        downloadInvoice(invoiceId);
    });
});

function attachButtonHandlers() {
    // View invoice button
    $('.view-invoice-btn').off('click').on('click', function() {
        const invoiceId = $(this).data('invoice-id');
        loadInvoiceDetails(invoiceId);
    });
    
    // Download invoice button
    $('.download-btn').off('click').on('click', function() {
        const invoiceId = $(this).data('invoice-id');
        downloadInvoice(invoiceId);
    });
}

// SEPARATE function for invoice creation
function handleInvoiceFormSubmission(form) {
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creating...').prop('disabled', true);
    
    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#createInvoiceModal').modal('hide');
                form[0].reset();
                showToast('success', response.message || 'Invoice created successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                submitBtn.html(originalText).prop('disabled', false);
                alert(response.message || 'Operation failed');
            }
        },
        error: function(xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            handleFormErrors(xhr);
        }
    });
}

function handleFormErrors(xhr) {
    if (xhr.responseJSON && xhr.responseJSON.errors) {
        let errors = xhr.responseJSON.errors;
        let errorMessage = 'Please fix the following errors:\n';
        Object.keys(errors).forEach(function(key) {
            errorMessage += '- ' + errors[key][0] + '\n';
        });
        alert(errorMessage);
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
        alert(xhr.responseJSON.message);
    } else {
        alert('Error processing request. Please try again.');
    }
}

function loadInvoiceDetails(invoiceId) {
    $.ajax({
        url: "{{ route('invoices.show', ':id') }}".replace(':id', invoiceId),
        method: 'GET',
        success: function(response) {
            $('#invoiceDetailsContent').html(response.html);
            $('#viewInvoiceModal').modal('show');
        },
        error: function(xhr) {
            alert('Error loading invoice details');
        }
    });
}

function downloadInvoice(invoiceId) {
    $.ajax({
        url: "{{ route('invoices.download', ':id') }}".replace(':id', invoiceId),
        method: 'GET',
        success: function(response) {
            if (response.download_url && response.download_url !== '#') {
                window.open(response.download_url, '_blank');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert('Error downloading invoice');
        }
    });
}

function showToast(type, message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to container
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    toastContainer.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endsection