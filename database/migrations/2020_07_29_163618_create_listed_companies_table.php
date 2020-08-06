<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListedCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listed_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('exchange_id');
            $table->string('symbol');
            $table->integer('face_value')->nullable();
            $table->string('isin')->nullable();
            $table->string('industry')->nullable();
            $table->tinyInteger('is_fno_available')->default(1);
            $table->tinyInteger('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listed_companies');
    }
}
