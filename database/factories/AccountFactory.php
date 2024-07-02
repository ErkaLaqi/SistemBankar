<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->company,
            'IBAN' => $this->faker->unique()->bankAccountNumber,
            'balance' => $this->faker->numberBetween(1000, 100000),
            'currency' => $this->faker->randomElement(['ALL', 'EUR', 'USD', 'GBP']),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
        ];
    }
}
