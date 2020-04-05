<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('tbl_admin')->insert(
            [

            'admin_email' => 'admin@gmail.com',
            'admin_password' => '123456',
            'admin_name' => 'st nguyen',
            'admin_phone' => '0795711020'
        ]
        );

    }
}

