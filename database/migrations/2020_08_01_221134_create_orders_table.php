<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('stock_id');
            $table->integer('exchange_id');
            $table->integer('instrument_type_id');
            $table->date('expiry_date')->nullable();
            $table->integer('strike_price')->nullable();
            $table->enum('option_type', ['ce', 'pe'])->nullable();
            $table->integer('trade_type_id');
            $table->enum('order_type', ['b', 's']);
            $table->decimal('price', 7,2);
            $table->integer('qty');
            $table->tinyInteger('is_mtf_opted')->default(0);
            $table->decimal('net_value', 11,2)->nullable();
            $table->decimal('pnl', 10,2)->nullable();
            $table->decimal('pnl_percentage', 6,2)->nullable();
            $table->decimal('net_pnl', 11,2)->nullable();
            $table->decimal('brokerage', 8,2)->nullable();
            $table->decimal('tax', 4,2)->nullable();
            $table->decimal('net_amount', 11,2)->nullable();
            $table->date('bought_on')->nullable();
            $table->date('sold_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
