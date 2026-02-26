<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => 'noimage.png',
            'condition' => $this->faker->numberBetween(1, 4),
            'name' => $this->faker->word,
            'brand' => $this->faker->company(),
            'description' => $this->faker->realText(),
            'price' =>  $this->faker->randomNumber(),
            'account_id' => $this->faker->randomNumber(),
        ];
    }
}
