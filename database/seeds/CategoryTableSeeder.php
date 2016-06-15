<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'fid' => 0,
            'cat_name' => 'Home',
            'description' => 'None',
            'cat_image' => 'upload/default.jpg',
            'sort' => 0,
            'display' => 'show',
            'path' => '0|1|0',
            'type' => 'local',
        ]);
    }
}
