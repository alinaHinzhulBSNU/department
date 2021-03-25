<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigitNotNull,
            'subject_id' => $this->faker->randomDigitNotNull,
            'student_id' => $this->faker->randomDigitNotNull,
            'grade' => $this->faker->numberBetween($min = 0, $max = 100),  
            'semester' => $this->faker->numberBetween($min = 1, $max = 12),  
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}
