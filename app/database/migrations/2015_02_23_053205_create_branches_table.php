<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('branches', function ($table) {
        	$table->engine ='InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 120)->unique();
            $table->string('address', 255);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('post_code', 45);
            $table->string('country', 75);
            $table->tinyInteger('status')->default(0);
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
		Schema::drop('branches');
	}

}
