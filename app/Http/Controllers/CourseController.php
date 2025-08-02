<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('instructor');

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by price
        if ($request->price === 'free') {
            $query->where('price', 0);
        } elseif ($request->price === 'paid') {
            $query->where('price', '>', 0);
        }

        $courses = $query->latest()->get();

        return view('courses.index', compact('courses'));
    }


    public function create()
    {
        if (auth()->user()->role !== 'instructor') {
            abort(403, 'Only instructors can create courses.');
        }

        return view('courses.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'instructor') {
            abort(403);
        }

       $data = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'difficulty' => 'required|string|in:beginner,intermediate,advanced',
            'duration' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|max:5120', // 50MB max
        ]);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('courses', 'public');
        }

        $data['duration'] = $request->input('duration');
        $data['user_id'] = auth()->id();
        Course::create($data);


        return redirect()->route('courses.index')->with('success', 'Course created!');
    }

    public function show(Course $course)
    {
        $user = auth()->user();
        $isEnrolled = false;

        if ($user && $user->role === 'student') {
            $isEnrolled = $user->enrolledCourses->contains($course->id);
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }


    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }


    public function update(Request $request, Course $course)
    {
        $course->update($request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'difficulty' => 'required|string|in:beginner,intermediate,advanced',
            'duration' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|max:5120', // 5MB max
        ]));

        return redirect()->route('courses.show', $course)->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted.');
    }

    public function students(Course $course)
    {
        if ($course->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $students = $course->students; // uses the 'enrollments' pivot

        return view('courses.students', compact('course', 'students'));
    }

    public function landing()
    {
        $courses = \App\Models\Course::latest()->take(6)->get(); // Fetch latest 6 courses
        return view('home', compact('courses')); // or 'welcome' if you're using welcome.blade.php
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $courses = Course::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('instructor')
            ->get();

        return view('courses.search_results', compact('courses', 'query'));
    }



}
