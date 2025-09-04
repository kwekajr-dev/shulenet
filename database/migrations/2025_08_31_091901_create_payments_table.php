<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('method'); // card, bank_transfer, paypal, etc.
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at');
            $table->string('status'); // pending, completed, failed
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('invoice_id');
            $table->index('transaction_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};