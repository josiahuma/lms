<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizQuestion;


class QuizQuestionController extends Controller
{
    //
    public function destroy(QuizQuestion $question)
    {
        $lessonId = $question->quiz->lesson_id;

        // Delete all associated answers
        $question->answers()->delete();
        $question->delete();

        return redirect()->route('quizzes.edit', $question->quiz)
                         ->with('success', 'Question deleted successfully.');
    }

}
