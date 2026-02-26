<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'テスト1',
                'email' => 'aaa@gmail.com',
                'password' => Hash::make('password1'),
            ],
            [
                'name' => 'テスト2',
                'email' => 'bbb@gmail.com',
                'password' => Hash::make('password2'),
            ],
            [
                'name' => 'テスト3',
                'email' => 'ccc@gmail.com',
                'password' => Hash::make('password1'),
            ],
        ]) ;
    }
}
