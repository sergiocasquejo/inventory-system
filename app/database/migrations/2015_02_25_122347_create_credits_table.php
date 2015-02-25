<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('credits', function($table){
			$table->engine ='InnoDB';
			$table->string('customer_name', 120);
			$table->string('address', 120);
			$table->string('contact_number', 30);
			$table->increments('credit_id')->unsigned();
			$table->string('product', 255);
			$table->float('quantity');
			$table->string('uom', 120);
			$table->decimal('total_amount', 10, 2);
			$table->text('comments');
			$table->date('date_of_credit');
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
		Schema::drop('credits');
	}

}
