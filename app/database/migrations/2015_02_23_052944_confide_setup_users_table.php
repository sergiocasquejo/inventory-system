<?php

use Illuminate\Database\Migrations\Migration;

class ConfideSetupUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Creates the users table
        Schema::create('users', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('username', 100)->unique();
            $table->string('email')->unique();
            $table->string('contact_no');
            $table->string('address');
            $table->string('birthdate');
            $table->string('password');
            $table->string('display_name', 70);
            $table->string('first_name', 70);
            $table->string('last_name', 70);
            $table->tinyInteger('is_admin')->default(0);
            $table->string('confirmation_code');
            $table->string('remember_token')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->tinyInteger('status')->default(0);
            $table->integer('branch_id');
            $table->timestamps();
            $table->softDeletes();
        });

        // Creates password reminders table
        Schema::create('password_reminders', function ($table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('password_reminders');
        Schema::drop('users');
    }
}
