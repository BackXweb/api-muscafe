<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'manager_id' => rand(1, 4),
            'role_id' => rand(3, 5),
            'name' => $this->faker->name(),
            'login' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
            'bitrix_link' => $this->faker->url(),
            'subscribe_end' => rand(0, 1) ? $this->faker->dateTime() : null
        ];
    }
}
