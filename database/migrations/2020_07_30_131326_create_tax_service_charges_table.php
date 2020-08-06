<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxServiceChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_service_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('trade_id');
            $table->decimal('gst', 8,2)->nullable();
            $table->decimal('stt', 8,2)->nullable();
            $table->decimal('sebi', 6,2)->nullable();
            $table->decimal('turn_over', 8,2)->nullable();
            $table->decimal('stamp_duty', 8,2)->nullable();
            $table->integer('mtf')->nullable();
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
        Schema::dropIfExists('tax_service_charges');
    }
}
