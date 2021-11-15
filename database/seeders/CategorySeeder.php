<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 7; $i++) :
            DB::table('categories')->insert([
                'title' => 'Category ' . $i,
                'parent_id' => 0
            ]);
        endfor;
    }
}
