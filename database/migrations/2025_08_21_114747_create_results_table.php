<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained();
        $table->string('term');
        $table->string('subject');
        $table->decimal('score', 5, 2);
        $table->string('grade');
        $table->text('teacher_comment')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
