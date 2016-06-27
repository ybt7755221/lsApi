<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fields')->insert([[
            'label' => 'Title',
            'key' => 'title',
            'params' => 'LsAPI Platform',
            'publish' => 1,
            'field_type' => 'TEXT',
            'user_id' => 1,
            'hits' => 0,
        ],[
            'label' => 'Description',
            'key' => 'description',
            'params' => 'This is a openApi Platform',
            'publish' => 1,
            'field_type' => 'TEXT',
            'user_id' => 1,
            'hits' => 0,
        ],[
            'label' => 'Contact Email',
            'key' => 'contact_email',
            'params' => 'example@example.com',
            'publish' => 1,
            'field_type' => 'TEXT',
            'user_id' => 1,
            'hits' => 0,
        ],[
            'label' => 'Author',
            'key' => 'author',
            'params' => 'burtyu',
            'publish' => 1,
            'field_type' => 'TEXT',
            'user_id' => 1,
            'hits' => 0,
        ],[
            'label' => 'Keyboard',
            'key' => 'keyboard',
            'params' => 'lsAPI,Burt,laravel,PHP,HTML5,CSS3,JacasScript,MySql',
            'publish' => 1,
            'field_type' => 'TEXT',
            'user_id' => 1,
            'hits' => 0,
        ],[
            'label' => 'Record Number',
            'key' => 'record_number',
            'params' => 'Copyright © 2009-2016 LSAPI',
            'publish' => 1,
            'field_type' => 'TEXT',
            'user_id' => 1,
            'hits' => 0,
        ]]);
    }
}
