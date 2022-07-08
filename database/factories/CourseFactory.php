<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lecturers = User::all()->pluck('id')->toArray();
        return [
            'course_id' => uniqid('COU-'),
            'title' => fake()->sentence(fake()->numberBetween(2,3)),
            'code' => 'CMP ' . fake()->numberBetween(111,999),
            'lecturer' => fake()->randomElement($lecturers),
            'total_students' => fake()->numberBetween(20,50)
        ];
    }
}
