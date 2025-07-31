<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->with('instructor')->get();
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
        ]);

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
