<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 21; $i++) :
            DB::table('sub_categories')->insert([
                'title' => 'Subcategory ' . $i,
                'category_id' => rand(1, 6)
            ]);
        endfor;
    }
}
