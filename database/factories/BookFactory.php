<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_code' => 'bk-' . $this->faker->unique()->numberBetween(1,100),
            'book_title' => $this->faker->sentence(mt_rand(1,4)),
            'author' => $this->faker->name(),
            'publisher' => $this->faker->word(),
            'available_stock' => 10,
            'total_stock'   => 10
        ];
    }
}
