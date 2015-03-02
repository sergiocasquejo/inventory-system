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
        	$table->engine ='InnoDB';
            $table->increments('stock_on_hand_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->float('total_stocks')->unsigned();
            $table->string('uom', 120);
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
		Schema::table('stocks_on_hand', function (Blueprint $table) {
            $table->dropForeign('stocks_on_hand_product_id_foreign');
            $table->dropForeign('stocks_on_hand_branch_id_foreign');
        });

		Schema::drop('stocks_on_hand');
	}

}
