<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->decimal('avg_buy_price', 11,2)->nullable();
            $table->integer('buy_qty')->nullable();
            $table->decimal('avg_sell_price', 11,2)->nullable();
            $table->integer('sell_qty')->nullable();
            $table->decimal('net_buy_value', 11,2)->nullable();
            $table->decimal('net_sell_value', 11,2)->nullable();
            $table->decimal('net_pnl', 11,2)->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('portfolios');
    }
}
