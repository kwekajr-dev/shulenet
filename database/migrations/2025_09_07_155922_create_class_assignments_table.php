<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->string('academic_year');
            $table->date('assignment_date')->useCurrent();
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('class_id');
            $table->index('academic_year');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_assignments');
    }
};