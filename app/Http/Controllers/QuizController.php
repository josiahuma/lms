<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\QuizSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $correct = 0;
        $total = $lesson->quiz->questions->count();

        foreach ($lesson->quiz->questions as $question) {
            $answerId = $request->answers[$question->id] ?? null;
            if ($answerId && $question->correctAnswer && $answerId == $question->correctAnswer->id) {
                $correct++;
            }
        }

        $score = ($total > 0) ? ($correct / $total) * 100 : 0;

        QuizAttempt::create([
            'user_id' => Auth::id(),
            'lesson_id' => $lesson->id,
            'score' => $score,
        ]);

        // Mark lesson as completed
        $lesson->completions()->firstOrCreate(['user_id' => Auth::id()]);

        // Find next lesson
        $nextLesson = Lesson::where('course_id', $lesson->course_id)
                            ->where('order', '>', $lesson->order)
                            ->orderBy('order')
                            ->first();

        if ($nextLesson) {
            return redirect()->route('lessons.show', $nextLesson->id)
                ->with('success', 'Quiz submitted! Moving to next lesson.');
        }

        return redirect()->route('student.dashboard')
            ->with('success', 'You’ve completed the final lesson. Download your certificate!');
    }

    public function takePaginated(Request $request, Lesson $lesson)
    {
        $quiz = $lesson->quiz;

        if (!$quiz) {
            return redirect()->route('lessons.show', $lesson->id)
                ->with('error', 'Quiz not found for this lesson.');
        }

        $questionIndex = (int) $request->query('question', 0);
        $questions = $quiz->questions;

        if (!isset($questions[$questionIndex])) {
            return redirect()->route('lessons.show', $lesson->id)
                ->with('error', 'Question not found.');
        }

        $question = $questions[$questionIndex];

        return view('quizzes.take-paginated', [
            'lesson' => $lesson,
            'quiz' => $quiz,
            'question' => $question,
            'questionIndex' => $questionIndex,
        ]);
    }

    public function storePaginatedAnswer(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'selected_option' => 'required|integer',
            'current_question' => 'required|integer',
        ]);

        $answers = session()->get('quiz_answers', []);
        $answers[$data['question_id']] = $data['selected_option']; // store ID
        session()->put('quiz_answers', $answers);


        // Get total number of questions for this lesson's quiz
        $totalQuestions = $lesson->quiz->questions()->count();

        // If last question, redirect to submit page
        if ($data['current_question'] + 1 >= $totalQuestions) {
            return redirect()->route('lessons.quiz.paginated.submit', ['lesson' => $lesson->id]);
        }

        // Else go to next question
        return redirect()->route('lessons.quiz.paginated', [
            'lesson' => $lesson->id,
            'question' => $data['current_question'] + 1
        ]);
    }

    public function submitPaginated(Lesson $lesson)
    {
        $answers = session()->get('quiz_answers', []);
        $correct = 0;

        foreach ($lesson->quiz->questions as $question) {
            $selectedAnswerId = $answers[$question->id] ?? null;

            if ($selectedAnswerId) {
                $selectedAnswer = $question->answers()->find($selectedAnswerId);
                if ($selectedAnswer && $selectedAnswer->is_correct) {
                    $correct++;
                }
            }
        }

        $total = $lesson->quiz->questions->count();
        $percentage = ($total > 0) ? ($correct / $total) * 100 : 0;

        // ✅ Save quiz attempt
        QuizSubmission::create([
            'user_id' => Auth::id(),
            'quiz_id' => $lesson->quiz->id,
            'score' => $percentage,
            'correct_answers' => $correct,
        ]);

        session()->forget('quiz_answers');

        return redirect()->route('lessons.quiz.result', ['lesson' => $lesson->id])
            ->with('score', $correct)
            ->with('percentage', $percentage);
    }



    public function result(Lesson $lesson)
    {
        $score = session('score');
        $percentage = session('percentage');
        $total = $lesson->quiz->questions->count();

        return view('quizzes.result', compact('lesson', 'score', 'percentage', 'total'));
    }

}
