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
        	$table->engine ='InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 255)->unique();
            $table->text('description');
            $table->text('comments');
            $table->tinyInteger('status')->default(0);
            $table->integer('encoded_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
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
