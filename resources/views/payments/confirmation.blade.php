@extends('layouts.app')

@section('title', 'Confirm Payment')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Confirm Payment</h1>
            <p class="text-muted">Verify and confirm payment for invoice</p>
        </div>
        <div>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Invoices
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Confirmation</h6>
                </div>
                <div class="card-body">
                    @if($invoice)
                    <!-- Invoice Summary -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Invoice Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Student:</strong> {{ $invoice->student->name }}</p>
                                <p><strong>Invoice #:</strong> #{{ $invoice->id }}</p>
                                <p><strong>Title:</strong> {{ $invoice->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Amount:</strong> TZS {{ number_format($invoice->amount, 2) }}</p>
                                <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge badge-{{ $invoice->status === 'pending' ? 'warning' : ($invoice->status === 'paid' ? 'success' : 'secondary') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($invoice->status === 'pending')
                    <!-- Payment Form -->
                    <form id="confirmPaymentForm" action="{{ route('invoices.confirm-payment', $invoice->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="payment_method">Payment Method *</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                            @error('payment_method')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="transaction_reference">Transaction Reference</label>
                            <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" 
                                value="{{ old('transaction_reference') }}" placeholder="Optional reference number">
                            @error('transaction_reference')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                placeholder="Any additional notes">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Confirm Payment
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        This invoice has already been marked as {{ $invoice->status }}.
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Return to Invoices
                        </a>
                    </div>
                    @endif
                    
                    @else
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Invoice not found.
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Return to Invoices
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle form submission
    $('#confirmPaymentForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Payment confirmed successfully!');
                    setTimeout(() => {
                        window.location.href = "{{ route('invoices.index') }}";
                    }, 1500);
                } else {
                    submitBtn.html(originalText).prop('disabled', false);
                    alert(response.message || 'Payment confirmation failed');
                }
            },
            error: function(xhr) {
                submitBtn.html(originalText).prop('disabled', false);
                
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
                    alert('Error confirming payment. Please try again.');
                }
            }
        });
    });
    
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
});
</script>
@endsection