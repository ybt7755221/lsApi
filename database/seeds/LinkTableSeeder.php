<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('link')->insert([
            'url' => 'http://www.burtyu.com',
            'title' => 'Burt Yu Private Website',
            'thumb' => '/storage/uploads/default.png',
            'description' => 'Burt Yu Private Website',
            'status' => 'show',
        ]);
    }
}
