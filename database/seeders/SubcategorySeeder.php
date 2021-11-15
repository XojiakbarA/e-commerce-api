<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 21; $i++) :
            DB::table('categories')->insert([
                'title' => 'Subcategory ' . $i,
                'parent_id' => rand(1, 6)
            ]);
        endfor;
    }
}
