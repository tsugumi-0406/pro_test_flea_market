<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;

class AccountFactory extends Factory
{
    protected $model = Account::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'image' => 'noimage.png',
            'name' => $this->faker->name(),
            'post_code' => $this->faker->postcode(),
            'address' => $this->faker->prefecture(),
            'building' => $this->faker->secondaryAddress(),
        ];
    }
}
