<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //  keep the title column but make it nullable
            $table->string('title')->nullable()->change();
            
            
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Reverse the changes if needed
            $table->string('title')->nullable(false)->change();
        });
    }
}