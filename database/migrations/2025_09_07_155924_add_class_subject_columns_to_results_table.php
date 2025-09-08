<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            // Remove any existing columns first to avoid duplicates
            if (Schema::hasColumn('results', 'class_id')) {
                $table->dropColumn('class_id');
            }
            
            if (Schema::hasColumn('results', 'subject_id')) {
                $table->dropColumn('subject_id');
            }
            
            // Add new columns (NO foreign keys here)
            $table->unsignedBigInteger('class_id')->nullable()->after('student_id');
            $table->unsignedBigInteger('subject_id')->nullable()->after('class_id');
            
            // Add indexes only
            $table->index('class_id');
            $table->index('subject_id');
        });
    }

    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['class_id']);
            $table->dropIndex(['subject_id']);
            
            // Drop columns
            $table->dropColumn(['class_id', 'subject_id']);
        });
    }
};