<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseReview;
use App\Models\Course;  // ✅ Add this line to fix the error

class CourseReviewController extends Controller
{
    //
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        CourseReview::create([
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
