<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricingHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('product_price_history', function ($table) {
        	$table->engine ='InnoDB';
            $table->bigIncrements('price_history_id');
            $table->bigInteger('product_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->integer('supplier_id')->nullable();
            $table->decimal('supplier_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->string('per_unit', 120);
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

		Schema::table('product_price_history', function (Blueprint $table) {
            $table->dropForeign('product_price_history_product_id_foreign');
            $table->dropForeign('product_price_history_branch_id_foreign');
        });


		Schema::drop('product_price_history');
	}

}
