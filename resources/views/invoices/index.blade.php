@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Invoices & Payments</h5>
                </div>
                <div class="card-body">
                    @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Student</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td>INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        @if($invoice->relationLoaded('student') && $invoice->student)
                                            {{ $invoice->student->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $invoice->title }}</td>
                                    <td>${{ number_format($invoice->amount, 2) }}</td>
                                    <td class="{{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-danger' : '' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($invoice->status !== 'paid')
                                        <a href="{{ route('invoices.payment-form', $invoice->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-credit-card"></i> Pay Now
                                        </a>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>No Invoices Found</h4>
                        <p class="mb-0">You don't have any invoices at this time.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection