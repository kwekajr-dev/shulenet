<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="invoice-details">
    <div class="row">
        <div class="col-md-6">
            <h6><strong>Invoice Information</strong></h6>
            <p><strong>Invoice #:</strong> #{{ $invoice->id }}</p>
            <p><strong>Title:</strong> {{ $invoice->title }}</p>
            <p><strong>Amount:</strong> TZS {{ number_format($invoice->amount, 2) }}</p>
            <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
            <p><strong>Status:</strong> 
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
            </p>
            @if($invoice->paid_at)
                <p><strong>Paid At:</strong> {{ \Carbon\Carbon::parse($invoice->paid_at)->format('M d, Y H:i') }}</p>
            @endif
        </div>
        <div class="col-md-6">
            <h6><strong>Student Information</strong></h6>
            <p><strong>Student:</strong> {{ $invoice->student->name }}</p>
            <p><strong>Parent:</strong> {{ $invoice->student->parent->name }}</p>
            <p><strong>Email:</strong> {{ $invoice->student->parent->email }}</p>
            @if($invoice->student->parent->phone)
                <p><strong>Phone:</strong> {{ $invoice->student->parent->phone }}</p>
            @endif
        </div>
    </div>
    
    @if($invoice->description)
        <div class="row mt-3">
            <div class="col-12">
                <h6><strong>Description</strong></h6>
                <p>{{ $invoice->description }}</p>
            </div>
        </div>
    @endif

    @if($invoice->payments && $invoice->payments->count() > 0)
        <div class="row mt-3">
            <div class="col-12">
                <h6><strong>Payment History</strong></h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y H:i') }}</td>
                                    <td>{{ ucfirst($payment->method) }}</td>
                                    <td>TZS {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td><span class="badge badge-success">{{ ucfirst($payment->status) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>