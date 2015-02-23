<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('sales', function ($table) {
            $table->bigIncrements('sale_id');
            $table->integer('branch_id');
            $table->bigInteger('product_id');
            $table->float('quantity');
            $table->string('uom', 120);
            $table->decimal('total_amount', 10, 2);
            $table->integer('encoded_by');
            $table->timestamps();
            $table->foreign('encoded_by')
     			->references('id')->on('users')
     			->onDelete('cascade');

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
		Schema::drop('sales');
	}

}
