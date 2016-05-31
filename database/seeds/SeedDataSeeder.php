<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoryTableSeeder::class);
        $this->call(ContentTableSeeder::class);
        $this->call(FieldsTableSeeder::class);
        $this->call(LinkTableSeeder::class);
    }
}
