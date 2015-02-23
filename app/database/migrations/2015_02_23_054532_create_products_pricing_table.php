<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsPricingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('product_pricing', function ($table) {
        	$table->engine ='InnoDB';
            $table->increments('price_id');
            $table->bigInteger('product_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->decimal('price', 10, 2);
            $table->float('per_unit');
            $table->string('uom', 120);
            $table->tinyInteger('status')->default(0);
            $table->integer('encoded_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
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
		Schema::drop('product_pricing');
	}

}
