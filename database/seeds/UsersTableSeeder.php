<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'super_admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('lsApi123sa'),
            'status' => 4,
            'created_at' => '2016-05-26 12:00:00',
            'updated_at' => $_SERVER['REQUEST_TIME'],
        ]);
    }
}
