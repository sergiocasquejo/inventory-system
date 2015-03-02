<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the users table
        Schema::create('sales', function ($table) {
            $table->engine ='InnoDB';
            $table->bigIncrements('sale_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->decimal('supplier_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->float('quantity');
            $table->string('uom', 120);
            $table->decimal('total_amount', 10, 2);
            $table->text('comments');
            $table->date('date_of_sale');
            $table->integer('encoded_by')->unsigned()->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('encoded_by')
     			->references('id')->on('users')
     			->onDelete('set null');

     		$table->foreign('product_id')
     			->references('id')->on('products')
     			->onDelete('set null');

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
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_branch_id_foreign');
            $table->dropForeign('sales_product_id_foreign');
            $table->dropForeign('sales_encoded_by_foreign');
        });

		Schema::drop('sales');
	}

}

