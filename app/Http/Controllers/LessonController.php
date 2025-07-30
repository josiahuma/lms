<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        return view('lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        if (auth()->user()->role !== 'instructor') {
            abort(403, 'Only instructors can add lessons.');
        }

            $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        $course->lessons()->create([
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Lesson created!');
    }

    public function edit(Lesson $lesson)
    {
        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $lesson->update($request->validate([
            'title' => 'required',
            'content' => 'nullable',
            'video_url' => 'nullable|url',
        ]));

        return redirect()->route('courses.show', $lesson->course_id)->with('success', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('success', 'Lesson deleted.');
    }

    public function show(Lesson $lesson)
    {
        $lesson->load('quiz');
        return view('lessons.show', compact('lesson'));
    }

    public function quiz(Lesson $lesson)
    {
        return view('lessons.quiz', compact('lesson'));
    }

    public function submitQuiz(Request $request, Lesson $lesson)
    {
        // In a real app you'd validate and score this
        // For now, we'll just mark quiz as completed

        session()->flash('quiz_passed', true); // Flash a success session

        return redirect()->route('lessons.show', $lesson)->with('success', 'Quiz submitted!');
    }



}
