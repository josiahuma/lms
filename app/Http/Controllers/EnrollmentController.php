<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403, 'Only students can enroll.');
        }

        // Prevent duplicate enrollment
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        $user->enrolledCourses()->attach($course->id);

        return back()->with('success', 'You have successfully enrolled in this course.');
    }
}
