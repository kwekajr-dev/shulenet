<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Confirm payment for an invoice
     */
   
     
    private function generateTransactionId()
    {
        return 'TXN' . time() . rand(1000, 9999);
    }
}