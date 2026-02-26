<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = Item::create([
            'image' => 'item_image/Armani+Mens+Clock.jpg',
            'condition' => 1,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'account_id' => 1
        ]);

        $category1 = Category::where('name', 'ファッション')->first();
        $category2 = Category::where('name', 'アクセサリー')->first();
        $item->categories()->attach([$category1->id, $category2->id]);
            
        $item = Item::create([
            'image' => 'item_image/HDD+Hard+Disk.jpg',
            'condition' => 2,
            'name' => 'HDD',
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'account_id' => 1,
        ]);

        $category1 = Category::where('name', '家電')->first();
        $item->categories()->attach([$category1->id]);

        $item = Item::create([
            'image' => 'item_image/iLoveIMG+d.jpg',
            'condition' => 3,
            'name' => '玉ねぎ3束',
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => 300,
            'account_id' => 1,
        ]);

        $category1 = Category::where('name', 'キッチン')->first();
        $item->categories()->attach([$category1->id]);

        $item = Item::create([
            'image' => 'item_image/Leather+Shoes+Product+Photo.jpg',
            'condition' => 4,
            'name' => '革靴',
            'brand' => '',
            'description' => 'クラッシックなデザインの革靴',
            'price' => 4000,
            'account_id' => 1,
        ]);

        $category1 = Category::where('name', 'ファッション')->first();
        $category2 = Category::where('name', 'メンズ')->first();
        $item->categories()->attach([$category1->id, $category2->id]);

        $item = Item::create([
            'image' => 'item_image/Living+Room+Laptop.jpg',
            'condition' => 1,
            'name' => 'ノートPC',
            'brand' => '',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'account_id' => 1,
        ]);

        $category1 = Category::where('name', '家電')->first();
        $item->categories()->attach([$category1->id]);

        $item = Item::create([
            'image' => 'item_image/Music+Mic+4632231.jpg',
            'condition' => 2,
            'name' => 'マイク',
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'price' => '8000',
            'account_id' => 2,
        ]);

        $category1 = Category::where('name', '家電')->first();
        $item->categories()->attach([$category1->id]);

        $item = Item::create([
            'image' => 'item_image/Purse+fashion+pocket.jpg',
            'condition' => 3,
            'name' => 'ショルダーバッグ',
            'brand' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
            'account_id' => 2,
        ]);

        $category1 = Category::where('name', 'ファッション')->first();
        $category2 = Category::where('name', 'レディース')->first();
        $item->categories()->attach([$category1->id, $category2->id]);
    
        $item = Item::create([
            'image' => 'item_image/Tumbler+souvenir.jpg',
            'condition' => 4,
            'name' => 'タンブラー',
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => '500',
            'account_id' => 2,
        ]);

        $category1 = Category::where('name', 'キッチン')->first();
        $item->categories()->attach([$category1->id]);

        $item = Item::create([
            'image' => 'item_image/Waitress+with+Coffee+Grinder.jpg',
            'condition' => 1,
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
            'account_id' => 2,
        ]);

        $category1 = Category::where('name', 'キッチン')->first();
        $item->categories()->attach([$category1->id]);

        $item = Item::create([
            'image' => 'item_image/外出メイクアップセット.jpg',
            'condition' => 2,
            'name' => 'メイクセット',
            'brand' => '',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
            'account_id' => 2,
        ]);

        $category1 = Category::where('name', 'コスメ')->first();
        $item->categories()->attach([$category1->id]);
    }
}
