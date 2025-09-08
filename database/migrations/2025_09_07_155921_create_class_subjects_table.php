<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->string('subject_name');
            $table->string('subject_code')->unique();
            $table->decimal('max_marks', 5, 2)->default(100);
            $table->decimal('pass_marks', 5, 2)->default(40);
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();
            
            // Indexes
            $table->index('class_id');
            $table->index('subject_code');
            $table->index('teacher_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_subjects');
    }
};