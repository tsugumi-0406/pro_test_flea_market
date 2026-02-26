<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Account;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            [
                'user_id' => 1,
                'image' => 'account_image/3rhYJcD94v8n8gYOY9Nos4dmfwapVoZTbATHBTZT.jpg',
                'name' => 'テスト1',
                'post_code' => '123-4567',
                'address' => '愛知県',
                'building' => '建物１',
            ],
            [
                'user_id' => 2,
                'image' => 'account_image/9TwfOs8uYF0aMoo1Jyq8DxhN39MQqCOsOaLeMobb.jpg',
                'name' => 'テスト2',
                'post_code' => '234-5678',
                'address' => '岐阜県',
                'building' => '建物２',
            ],
            [
                'user_id' => 3,
                'image' => '',
                'name' => 'テスト3',
                'post_code' => '345-6789',
                'address' => '三重県',
                'building' => 'account_image/noimage.png',
            ],
        ]);
    }
}
