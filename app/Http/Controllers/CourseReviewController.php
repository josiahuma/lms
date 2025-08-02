<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ✅ Add this
use Illuminate\Http\Request;
use App\Models\CourseReview;
use App\Models\Course;  // ✅ Add this line to fix the error

class CourseReviewController extends Controller
{
    use AuthorizesRequests; // ✅ Add this trait
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

    public function edit(Course $course, CourseReview $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review', 'course'));
    }

    public function update(Request $request, Course $course, CourseReview $review)
    {
        $this->authorize('update', $review);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);
        $review->update($request->only('rating', 'review'));
        return redirect()->route('courses.show', $course)->with('success', 'Review updated!');
    }

    public function destroy(Course $course, CourseReview $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return redirect()->route('courses.show', $course)->with('success', 'Review deleted.');
    }

}
