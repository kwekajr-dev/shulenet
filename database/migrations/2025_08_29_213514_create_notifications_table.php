<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // attendance, grade, invoice, event, announcement, system
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('related_type')->nullable(); // Polymorphic relation type
            $table->unsignedBigInteger('related_id')->nullable(); // Polymorphic relation ID
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable();
            $table->string('priority')->default('normal'); 
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'read_at']);
            $table->index(['related_type', 'related_id']);
            $table->index('type');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};