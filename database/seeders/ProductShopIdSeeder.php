<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductShopIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 56; $i < 97; $i++) :
            $product = Product::find($i);
            $product->shop_id = rand(1, 8);
            $product->save();
        endfor;
    }
}
