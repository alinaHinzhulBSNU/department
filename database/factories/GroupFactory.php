<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigitNotNull,
            'number' => Str::random(3),
            'course' => $this->faker->randomDigitNotNull,
            'major' => Str::random(3),
            'start_year' => $this->faker->numberBetween($min = 2015, $max = 2020),
            'end_year' => $this->faker->numberBetween($min = 2020, $max = 2025),
        ];
    }
}
