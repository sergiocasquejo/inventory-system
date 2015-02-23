<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksOnHandTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('stocks_on_hand', function ($table) {
            $table->increments('stock_on_hand');
            $table->bigInteger('product_id');
            $table->integer('branch_id');
            $table->float('total_stocks');
            $table->string('uom', 120);
            $table->tinyInteger('status')->default(0);
            $table->foreign('product_id')
     			->references('id')->on('products')
     			->onDelete('cascade');

     		$table->foreign('branch_id')
     			->references('id')->on('branches')
     			->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stocks_on_hand');
	}

}
