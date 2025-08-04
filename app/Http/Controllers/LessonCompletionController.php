<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\Auth;

class LessonCompletionController extends Controller
{
    public function store(Lesson $lesson)
    {
        LessonCompletion::firstOrCreate([
            'user_id' => Auth::id(),
            'lesson_id' => $lesson->id,
        ]);

        // Redirect to quiz if it exists
        if ($lesson->quiz) {
            return redirect()->route('lessons.quiz.paginated', ['lesson' => $lesson->id, 'question' => 0]);
        }

        // Check if order is not null before comparison
        $nextLesson = null;

        if (!is_null($lesson->order)) {
            $nextLesson = Lesson::where('course_id', $lesson->course_id)
                ->where('order', '>', $lesson->order)
                ->orderBy('order')
                ->first();
        }

        if ($nextLesson) {
            return redirect()->route('lessons.show', $nextLesson->id)
                ->with('success', 'Lesson completed. Proceed to next.');
        }

        // If no next lesson, go to student dashboard
        return redirect()->route('student.dashboard')
            ->with('success', 'Course completed. You can now download your certificate.');
    }


}
