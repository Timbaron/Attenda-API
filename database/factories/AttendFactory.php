<?php

namespace Database\Factories;

use App\Models\Attend;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attend>
 */
class AttendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $courseId = Course::all()->pluck('course_id')->toArray();
        $attendees = [];
        
        for($i = 0; $i < fake()->numberBetween(5,10); $i++) {
            array_unshift($attendees, fake()->firstName(). ' ' . fake()->lastName());
        }
        return [
            'attendance_id' => uniqid('ATD-'),
            'course_id' => fake()->randomElement($courseId),
            'attendees' => json_encode($attendees),
        ];
    }
}
