<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimesToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('served_at')->nullable()->after('status');
            $table->timestamp('delivered_at')->nullable()->after('served_at');
            $table->integer('preparation_minutes')->nullable()->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['served_at', 'delivered_at', 'preparation_minutes']);
        });
    }
}
