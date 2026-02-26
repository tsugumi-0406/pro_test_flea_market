<?php

namespace Database\Factories;
use App\Models\Order;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => $this->faker->randomNumber(),
            'account_id' => $this->faker->randomNumber(),
            'method' => 1,
            'post_code' => $this->faker->postcode(),
            'address' => $this->faker->prefecture(),
        ];
    }
}
