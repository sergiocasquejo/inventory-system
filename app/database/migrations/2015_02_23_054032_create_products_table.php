<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('products', function ($table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->text('description');
            $table->text('comments');
            $table->tinyInteger('status')->default(0);
            $table->integer('encoded_by');
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
		Schema::drop('products');
	}

}
