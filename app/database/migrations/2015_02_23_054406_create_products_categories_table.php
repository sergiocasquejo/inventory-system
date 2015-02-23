<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('products_categories', function ($table) {
            $table->increments('category_id');
            $table->string('name', 255)->unique();
            $table->string('slug', 255);
            $table->text('description');
            $table->tinyInteger('status')->default(0);
            $table->string('image',120);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_categories');
	}

}
