<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitOfMeasuresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('unit_of_measures', function ($table) {
        	$table->engine ='InnoDB';
            $table->increments('uom_id')->unsigned();
            $table->string('name', 120)->unique();
            $table->string('label', 120)->unique();
            $table->tinyInteger('is_decimal')->unsigned()->default(0);
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
		Schema::drop('unit_of_measures');
	}

}
