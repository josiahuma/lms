<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonCompletionController extends Controller
{
    //
    public function store(Request $request, Lesson $lesson)
    {
        $user = auth()->user();

        // Attach the lesson to the user's completed list (using a pivot)
        $user->completedLessons()->syncWithoutDetaching([$lesson->id]);

        return redirect()->back()->with('success', 'Lesson marked as completed!');
    }
}
