<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in', function (Blueprint $table) {
            $table->id('id_stock_in');
            $table->integer('ware_house')->unsigned();
            // $table->string('material_id', '128');
            $table->integer('balance_id')->unsigned();
            $table->integer('value_in')->unsigned();
            $table->integer('total_price_in')->unsigned();
            $table->softDeletes();
            $table->date('date_in');
            // $table->timeTz('time_in');
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
        Schema::dropIfExists('stock_in');
    }
}
