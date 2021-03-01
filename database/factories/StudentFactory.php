<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigitNotNull,
            'group_id' => $this->faker->randomDigitNotNull,
            'user_id' => $this->faker->randomDigitNotNull,
            'is_class_leader' => $this->faker->Boolean, 
            'has_grant' => $this->faker->Boolean, 
            'has_social_grant' => $this->faker->Boolean,
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}
