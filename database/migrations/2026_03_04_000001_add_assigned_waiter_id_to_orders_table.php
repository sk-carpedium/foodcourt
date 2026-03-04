<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_waiter_id')->nullable()->after('user_id');

            // keep foreign key if users table exists and you want referential integrity
            $table->foreign('assigned_waiter_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_waiter_id']);
            $table->dropColumn('assigned_waiter_id');
        });
    }
};