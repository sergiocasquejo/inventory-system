<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('expenses', function ($table) {
            $table->engine ='InnoDB';
            $table->bigIncrements('expense_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->string('name', 255);
            $table->decimal('total_amount', 10, 2);
            $table->float('quantity');
            $table->string('uom', 120);
            $table->text('comments');
            $table->tinyInteger('status')->default(0);
            $table->integer('encoded_by')->unsigned();
            $table->date('date_of_expense');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('encoded_by')
     			->references('id')->on('users')
     			->onDelete('cascade');

     		$table->foreign('branch_id')
     			->references('id')->on('branches')
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
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign('expenses_branch_id_foreign');
            $table->dropForeign('expenses_encoded_by_foreign');
        });

		Schema::drop('expenses');
	}

}
