<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);

        $this->call(UsersTableSeeder::class);

        $this->call(AccountsTableSeeder::class);

        $this->call(ItemsTableSeeder::class);

        $this->call(LikesTableSeeder::class);

        $this->call(CommentsTableSeeder::class);
    }
}
