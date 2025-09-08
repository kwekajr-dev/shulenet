<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e54c8;
            --secondary: #8f94fb;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .payment-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border: none;
        }
        
        .payment-card:hover {
            transform: translateY(-5px);
        }
        
        .payment-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        
        .payment-status-badge {
            font-size: 0.85rem;
            padding: 8px 15px;
            border-radius: 20px;
        }
        
        .payment-method {
            border: 2px solid #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method:hover, .payment-method.selected {
            border-color: var(--primary);
            background-color: #f8f9ff;
        }
        
        .payment-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        
        .btn-pay {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s;
        }
        
        .btn-pay:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
        }
        
        .invoice-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        
        .invoice-item:last-child {
            border-bottom: none;
        }
        
        .payment-history {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
            background: transparent;
        }
        
        .sidebar-card {
            height: 100%;
        }
        
        .student-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>School Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-file-invoice me-1"></i> Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-history me-1"></i> Payment History</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Notification Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="payment-card card">
                    <div class="payment-header text-center">
                        <h2 class="mb-0"><i class="fas fa-receipt me-2"></i>Invoice Payment</h2>
                    </div>
                    <div class="card-body">
                        <!-- Invoice Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Invoice Details</h5>
                                <p class="mb-1"><strong>Invoice #:</strong> INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p class="mb-1"><strong>Student:</strong> {{ $invoice->student->name }}</p>
                                <p class="mb-1"><strong>Issued:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                                <p class="mb-0"><strong>Due Date:</strong> 
                                    <span class="{{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-danger fw-bold' : 'text-success' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                        @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                        <i class="fas fa-exclamation-triangle ms-1"></i>
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5>Amount Due</h5>
                                <h2 class="text-primary">${{ number_format($invoice->amount, 2) }}</h2>
                                <span class="payment-status-badge badge 
                                    {{ $invoice->status === 'paid' ? 'bg-success' : 
                                       ($invoice->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                        </div>

                        <hr>

                        @if($invoice->status !== 'paid')
                        <!-- Payment Methods -->
                        <div class="mb-4">
                            <h5 class="mb-3">Select Payment Method</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="payment-method text-center" data-method="card">
                                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                                        <p class="mb-0">Credit/Debit Card</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-method text-center" data-method="bank">
                                        <i class="fas fa-university fa-2x mb-2"></i>
                                        <p class="mb-0">Bank Transfer</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-method text-center" data-method="paypal">
                                        <i class="fab fa-paypal fa-2x mb-2"></i>
                                        <p class="mb-0">PayPal</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form (Dynamically shown based on selection) -->
                        <div class="payment-details mb-4" id="card-details" style="display: none;">
                            <h5 class="mb-3">Card Details</h5>
                            <form id="card-payment-form" action="{{ route('invoices.process-payment', $invoice->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="card">
                                
                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="card_number" id="cardNumber" placeholder="1234 5678 9012 3456" required>
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expiryDate" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" name="expiry_date" id="expiryDate" placeholder="MM/YY" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" name="cvv" id="cvv" placeholder="123" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="cardName" class="form-label">Name on Card</label>
                                    <input type="text" class="form-control" name="card_name" id="cardName" placeholder="John Doe" required>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-pay">
                                        <i class="fas fa-lock me-2"></i> Pay Now
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="payment-details mb-4" id="bank-details" style="display: none;">
                            <h5 class="mb-3">Bank Transfer Instructions</h5>
                            <p>Please transfer the amount of <strong>${{ number_format($invoice->amount, 2) }}</strong> to the following account:</p>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>Bank Name:</strong> Example Bank</p>
                                <p class="mb-1"><strong>Account Name:</strong> School Name</p>
                                <p class="mb-1"><strong>Account Number:</strong> 1234 5678 9012 3456</p>
                                <p class="mb-1"><strong>Routing Number:</strong> 021000021</p>
                                <p class="mb-0"><strong>Reference:</strong> INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <p class="mt-3 text-muted">Your payment will be processed within 1-2 business days after the transfer is completed.</p>
                            
                            <form action="{{ route('invoices.process-payment', $invoice->id) }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="payment_method" value="bank_transfer">
                                
                                <div class="mb-3">
                                    <label for="transactionReference" class="form-label">Transaction Reference</label>
                                    <input type="text" class="form-control" name="transaction_reference" id="transactionReference" required>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-pay">
                                        <i class="fas fa-paper-plane me-2"></i> Confirm Transfer
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="payment-details mb-4" id="paypal-details" style="display: none;">
                            <h5 class="mb-3">PayPal Payment</h5>
                            <p>You will be redirected to PayPal to complete your payment of <strong>${{ number_format($invoice->amount, 2) }}</strong>.</p>
                            
                            <form action="{{ route('invoices.process-payment', $invoice->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="paypal">
                                
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-pay">
                                        <i class="fab fa-paypal me-2"></i> Pay with PayPal
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-2x mb-3"></i>
                            <h4>This invoice has been paid</h4>
                            <p class="mb-0">Payment completed on {{ $invoice->paid_at->format('M d, Y \a\t h:i A') }}</p>
                            <div class="mt-3">
                                <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-download me-2"></i> Download Receipt
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Invoice Items (if applicable) -->
                        @if(isset($invoice->items) && count($invoice->items) > 0)
                        <hr>
                        <div class="mt-4">
                            <h5>Invoice Items</h5>
                            <div class="list-group">
                                @foreach($invoice->items as $item)
                                <div class="list-group-item invoice-item">
                                    <div class="d-flex justify-content-between">
                                        <div>{{ $item->description }}</div>
                                        <div>${{ number_format($item->amount, 2) }}</div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="list-group-item invoice-item">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <div>Total Amount</div>
                                        <div>${{ number_format($invoice->amount, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment History Sidebar -->
            <div class="col-lg-4">
                <div class="card payment-card sidebar-card">
                    <div class="card-header payment-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Payment History</h5>
                    </div>
                    <div class="card-body">
                        @if($invoice->payments && $invoice->payments->count() > 0)
                        <div class="payment-history">
                            @foreach($invoice->payments as $payment)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-capitalize">{{ $payment->method }}</strong>
                                    <span class="badge bg-success">${{ number_format($payment->amount, 2) }}</span>
                                </div>
                                <div class="text-muted small">
                                    {{ $payment->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div class="small">
                                    Reference: {{ $payment->transaction_id }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-muted">No payment history found for this invoice.</p>
                        @endif
                        
                        <!-- Student Information -->
                        <hr>
                        <h6 class="mb-3">Student Information</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-3 me-3">
                                <i class="fas fa-user-graduate text-primary fa-lg"></i>
                            </div>
                            <div>
                                <strong>{{ $invoice->student->name }}</strong>
                                <p class="mb-0 small text-muted">Student</p>
                            </div>
                        </div>
                        
                        <!-- Parent Information -->
                        <h6 class="mb-3">Parent Information</h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-3 me-3">
                                <i class="fas fa-user text-primary fa-lg"></i>
                            </div>
                            <div>
                                <strong>{{ $invoice->student->parent->name }}</strong>
                                <p class="mb-0 small text-muted">Parent</p>
                                <p class="mb-0 small">{{ $invoice->student->parent->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2023 School Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            let selectedMethod = null;

            // Payment method selection
            $('.payment-method').click(function() {
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                
                selectedMethod = $(this).data('method');
                $('.payment-details').hide();
                $(`#${selectedMethod}-details`).show();
            });

            // Format card number input
            $('#cardNumber').on('input', function() {
                let value = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
                $(this).val(formatted);
            });

            // Format expiry date input
            $('#expiryDate').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                $(this).val(value);
            });

            // Restrict CVV to numbers only
            $('#cvv').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
            });
        });
    </script>
</body>
</html>