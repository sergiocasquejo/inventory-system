<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media', function($table){
			$table->engine ='InnoDB';
			$table->bigIncrements('media_id')->unsigned();
			$table->string('file', 255);
			$table->string('path', 255);
			$table->string('filename', 255);
			$table->integer('media_order');
			$table->bigInteger('mediable_id')->unsigned();
			$table->string('mediable_type', 255);
			$table->tinyInteger('is_primary')->default(0);
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
		Schema::drop('media');
	}

}
