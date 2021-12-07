<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductDescSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 56; $i < 106; $i++) :
            $product = Product::find($i);
            $product->desc = $faker->paragraph(10, false);
            $product->save();
        endfor;
    }
}
