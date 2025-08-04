<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizQuestionController extends Controller
{
    public function take(Lesson $lesson, $questionIndex)
    {
        $questions = $lesson->quiz->questions;
        $question = $questions[$questionIndex] ?? null;

        if (!$question) {
            return redirect()->route('lessons.show', $lesson->id)
                ->with('error', 'Invalid question.');
        }

        return view('quizzes.take-paginated', compact('lesson', 'question', 'questionIndex', 'questions'));
    }

    public function submit(Request $request, Lesson $lesson, $questionIndex)
    {
        $request->validate([
            'answer' => 'required|integer',
        ]);

        session()->put("quiz.{$lesson->id}.{$questionIndex}", $request->answer);

        $nextIndex = $questionIndex + 1;
        if ($nextIndex >= $lesson->quiz->questions->count()) {
            return redirect()->route('quizzes.submit.final', $lesson->id);
        }

        return redirect()->route('lessons.quiz.take', ['lesson' => $lesson->id, 'question' => $nextIndex]);
    }

    public function finalSubmit(Lesson $lesson)
    {
        $questions = $lesson->quiz->questions;
        $answers = session("quiz.{$lesson->id}", []);
        $correct = 0;

        foreach ($questions as $index => $question) {
            if (isset($answers[$index]) && $question->correctAnswer->id == $answers[$index]) {
                $correct++;
            }
        }

        $score = ($questions->count() > 0) ? ($correct / $questions->count()) * 100 : 0;

        $lesson->quizAttempts()->create([
            'user_id' => Auth::id(),
            'score' => $score,
        ]);

        session()->forget("quiz.{$lesson->id}");

        return redirect()->route('lessons.show', $lesson->id)
            ->with('success', "Quiz complete! Your score: {$score}%");
    }
}
