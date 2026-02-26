<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Like;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    echo ">>> LikesTableSeeder 実行中...\n";

    DB::table('likes')->insert([
        ['account_id' => 1, 'item_id' => 2],
    ]);
}
}
