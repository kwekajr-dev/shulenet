<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMessageNullableInNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Check if the column exists before trying to modify it
            if (Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'message')) {
                // Reverse the changes if needed
                $table->text('message')->nullable(false)->change();
            }
        });
    }
}