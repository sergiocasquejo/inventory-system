<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsToCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('products_to_categories', function ($table) {
        	$table->engine ='InnoDB';
            $table->increments('product_to_category_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('parent')->unsigned();
            $table->foreign('product_id')
     			->references('id')->on('products')
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
		Schema::table('products_to_categories', function (Blueprint $table) {
            $table->dropForeign('products_to_categories_product_id_foreign');
            $table->dropForeign('products_to_categories_category_id_foreign');
        });
		Schema::drop('products_to_categories');
	}

}
