<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigitNotNull,
            'name' => Str::random(10),
            'exam_type' => Str::random(10),
            'description' => Str::random(10),
            'credit' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 1, $max = 10),
            'teacher_id' => $this->faker->randomDigitNotNull,
        ];
    }
}
