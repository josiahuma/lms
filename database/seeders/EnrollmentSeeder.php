<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure you have some courses and students
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        // If not, create 3 students and 2 courses for testing
        if ($students->count() === 0) {
            $students = User::factory()->count(3)->create([
                'role' => 'student'
            ]);
        }

        if ($courses->count() === 0) {
            $instructor = User::where('role', 'instructor')->first();
            if (!$instructor) {
                $instructor = User::factory()->create(['role' => 'instructor']);
            }

            $courses = Course::factory()->count(2)->create([
                'user_id' => $instructor->id
            ]);
        }

        // Enroll each student in every course
        foreach ($courses as $course) {
            foreach ($students as $student) {
                $course->students()->syncWithoutDetaching($student->id);
            }
        }

        echo "✅ Test students enrolled successfully.\n";
    }
}
