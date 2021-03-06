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
			$table->bigIncrements('credit_id')->unsigned();
			$table->engine ='InnoDB';
			$table->integer('branch_id')->unsigned();
			$table->bigInteger('sale_id')->unsigned();
			$table->string('customer_name', 120);
			$table->string('address', 120);
			$table->string('contact_number', 30);
			$table->tinyInteger('is_paid');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('encoded_by')
     			->references('id')->on('users')
     			->onDelete('set null');

     		$table->foreign('sale_id')
     			->references('sale_id')->on('sales')
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

		// Schema::table('credits', function (Blueprint $table) {
  //           $table->dropForeign('credits_encoded_by_foreign');
  //       });
		Schema::drop('credits');
	}

}
