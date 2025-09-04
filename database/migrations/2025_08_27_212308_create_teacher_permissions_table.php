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
        Schema::create('teacher_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('teacher_roles')->onDelete('cascade');
            $table->string('permission');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate permissions for the same role
            $table->unique(['role_id', 'permission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_permissions');
    }
};