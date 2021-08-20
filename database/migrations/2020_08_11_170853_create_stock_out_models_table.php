<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOutModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_out', function (Blueprint $table) {
            $table->id('id_stock_out');
            $table->integer('member_id')->unsigned();
            // $table->string('material_id', '128');
            $table->integer('balance_id')->unsigned();
            $table->integer('room')->unsigned();
            $table->integer('value_out')->unsigned();
            $table->integer('total_price_out')->unsigned();
            $table->softDeletes();
            // $table->integer('important')->unsigned();
            // $table->integer('status')->unsigned()->default(0);
            $table->date('date_out');
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
        Schema::dropIfExists('stock_out');
    }
}
