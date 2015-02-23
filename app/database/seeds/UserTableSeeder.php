<?php

class UserTableSeeder extends Seeder {

    public function run()
	{
		
		if(!$user = DB::table('users')->insert(array(
				'display_name' => 'john', 
				'username' => 'owner',  
				'email' => 'michael@lightmedia.com.au', 
				'password' => Hash::make('admin223'),
				'confirmation_code' => md5(uniqid(mt_rand(), true)),
				'first_name' => 'John',
				'last_name' => 'Doe',
				'confirmed' => 1,
				'is_admin' => 1
				)))

		{
			$this->command->info($user->errors());
		}

	}

}