<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'item_id' => '1',
                'account_id' => '2',
                'method' => '1',
                'post_code' => '234-5678',
                'address' => '岐阜県',
                'status' => 'trading', 
            ],
            [
                'item_id' => '6',
                'account_id' => '1',
                'method' => '1',
                'post_code' => '234-5678',
                'address' => '愛知県',
                'status' => 'trading', 
            ]
        ]) ;
    }
}
