<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $use_any = rand(0, 1);

        return [
            'user_id' => rand(10, 100),
            'name' => $this->faker->company(),
            'address' => $use_any ? null : $this->faker->address(),
            'use_any' => $use_any
        ];
    }
}
