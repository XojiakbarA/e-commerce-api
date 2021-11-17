<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banners = [
            [
                'title' => '50% Off For Your First Shopping',
                'desc' => 'Probably the most random thing you have ever seen!',
                'image' => 'nike-shoes-1.jpeg'
            ],
            [
                'title' => 'BROWSE OUR PREMIUM PRODUCT',
                'desc' => 'Us which over of signs divide dominion deep fill bring they\'re meat beho upon own earth without morning over third. Their male dry. They are great appear whose land fly grass.',
                'image' => 'nike-shoes-2.jpeg'
            ],
            [
                'title' => '50% Off For Your First Shopping',
                'desc' => 'Probably the most random thing you have ever seen!',
                'image' => 'nike-shoes-3.jpeg'
            ]
        ];

        foreach ($banners as $banner) :
            DB::table('banners')->insert([
                'title' => $banner['title'],
                'desc' => $banner['desc'],
                'image' => $banner['image']
            ]);
        endforeach;
    }
}
