<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesToBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories_to_brands', function($table){
			$table->engine ='InnoDB';
			$table->increments('category_to_brand_id')->unsigned();
			$table->integer('category_id')->unsigned();
			$table->integer('brand_id')->unsigned();
			$table->foreign('brand_id')
     			->references('brand_id')->on('brands')
     			->onDelete('cascade');

     		$table->foreign('category_id')
     			->references('category_id')->on('categories')
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
		Schema::drop('categories_to_brands');
	}

}
