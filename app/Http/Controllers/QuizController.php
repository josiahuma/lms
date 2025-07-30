<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizSubmission;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class QuizController extends Controller
{
    public function create(Lesson $lesson)
    {
        return view('quizzes.create', compact('lesson'));
    }

    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'questions.*.question' => 'required|string',
            'questions.*.answers.*.answer_text' => 'required|string',
            'questions.*.correct_answer' => 'required|integer'
        ]);

        $quiz = Quiz::create(['lesson_id' => $lesson->id]);

        foreach ($request->input('questions') as $questionData) {
            $question = $quiz->questions()->create(['question' => $questionData['question']]);

            foreach ($questionData['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => ($index == $questionData['correct_answer']),
                ]);
            }
        }

        return redirect()->route('lessons.show', $lesson)->with('success', 'Quiz created successfully!');
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('lesson', 'questions.answers');
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions.*.question' => 'required|string',
            'questions.*.answers.*.answer_text' => 'required|string',
            'questions.*.correct_answer' => 'required|integer'
        ]);

        // Delete old questions and answers
        foreach ($quiz->questions as $question) {
            $question->answers()->delete();
            $question->delete();
        }

        foreach ($request->input('questions') as $questionData) {
            $question = $quiz->questions()->create(['question' => $questionData['question']]);

            foreach ($questionData['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => ($index == $questionData['correct_answer']),
                ]);
            }
        }

        return redirect()->route('lessons.show', $quiz->lesson)->with('success', 'Quiz updated!');
    }

    public function destroy(Quiz $quiz)
    {
        $lesson = $quiz->lesson;

        foreach ($quiz->questions as $question) {
            $question->answers()->delete();
            $question->delete();
        }

        $quiz->delete();

        return redirect()->route('lessons.show', $lesson)->with('success', 'Quiz deleted.');
    }

    public function view(Quiz $quiz)
    {
        $quiz->load('lesson', 'questions.answers');
        return view('quizzes.show', compact('quiz'));
    }

    // Paginated Quiz Flow
    public function takePaginated(Request $request, Lesson $lesson)
    {
        if ($request->query('q') == 0) {
            session()->forget("quiz.{$lesson->id}.answers"); // clear old answers
        }

        $quiz = $lesson->quiz()->with('questions.answers')->firstOrFail();
        $index = (int) $request->query('q', 0);
        $question = $quiz->questions[$index] ?? null;

        if (!$question) {
            return redirect()->route('lessons.quiz.paginated.submit', $lesson);
        }

        return view('quizzes.paginated', [
            'lesson' => $lesson,
            'quiz' => $quiz,
            'question' => $question,
            'index' => $index,
            'total' => $quiz->questions->count(),
        ]);
    }


    public function storePaginatedAnswer(Request $request, Lesson $lesson)
    {
        $index = (int) $request->input('index');
        $answerId = $request->input('answer_id');

        session()->put("quiz.{$lesson->id}.answers.{$index}", $answerId);

        return redirect()->route('lessons.quiz.paginated', ['lesson' => $lesson, 'q' => $index + 1]);
    }

    public function submitPaginated(Lesson $lesson)
    {
        $quiz = $lesson->quiz()->with('questions.answers')->firstOrFail();
        $answers = session("quiz.{$lesson->id}.answers", []);

        $score = 0;

        foreach ($quiz->questions as $index => $question) {
            $correctAnswer = $question->answers->firstWhere('is_correct', true);
            $submittedAnswerId = $answers[$index] ?? null;

            if ($submittedAnswerId && $submittedAnswerId == $correctAnswer->id) {
                $score++;
            }
        }

        $percentage = ($score / max(1, $quiz->questions->count())) * 100;

        QuizSubmission::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
        ]);

        // ✅ MARK LESSON AS COMPLETED
        $user = auth()->user();
        if ($percentage >= 80) {
            $user->completedLessons()->syncWithoutDetaching([$lesson->id]);
        }

        session()->forget("quiz.{$lesson->id}");

        return view('quizzes.result', compact('lesson', 'score', 'percentage'));
    }


    public function result(Lesson $lesson)
    {
        $quiz = $lesson->quiz;

        // Optionally fetch latest submission instead
        $submission = QuizSubmission::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->latest()->first();

        $score = $submission->score;
        $total = $quiz->questions->count();
        $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

        return view('quizzes.result', compact('lesson', 'score', 'total', 'percentage'));
    }

    public function showPaginated(Lesson $lesson, Request $request)
    {
        $questionIndex = (int) $request->query('question', 0);
        $quiz = $lesson->quiz()->with('questions.answers')->firstOrFail();

        if (!session()->has('quiz_answers')) {
            session(['quiz_answers' => []]);
        }

        if ($request->isMethod('post')) {
            $selectedAnswer = $request->input('answer');
            $questionId = $quiz->questions[$questionIndex - 1]->id;
            session()->put("quiz_answers.$questionId", $selectedAnswer);
        }

        if ($questionIndex >= $quiz->questions->count()) {
            return redirect()->route('lessons.quiz.result', $lesson);
        }

        $question = $quiz->questions[$questionIndex];

        return view('quizzes.take-paginated', compact('lesson', 'question', 'questionIndex'));
    }

     public function dashboard()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()->with('course.lessons.quiz')->get();
        $completions = LessonCompletion::where('user_id', $user->id)->pluck('lesson_id')->toArray();

        // Highest scores for each quiz
        $submissions = QuizSubmission::where('user_id', $user->id)
            ->select(DB::raw('MAX(score) as score, quiz_id'))
            ->groupBy('quiz_id')
            ->get()
            ->keyBy('quiz_id');

        // Prepare progress and certificate eligibility
        $progress = [];
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $total = $course->lessons->count();
            $completed = collect($course->lessons)->whereIn('id', $completions)->count();
            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

            // Check if all lessons are completed and each quiz score is 80%+
            $allCompleted = $completed === $total;
            $allPassed = true;

            foreach ($course->lessons as $lesson) {
                if ($lesson->quiz) {
                    $quiz = $lesson->quiz;
                    $maxScore = $quiz->questions->count();
                    $userScore = $submissions[$quiz->id]->score ?? 0;
                    if ($maxScore == 0 || ($userScore / $maxScore) < 0.8) {
                        $allPassed = false;
                    }
                }
            }

            $progress[$course->id] = [
                'completed' => $completed,
                'total' => $total,
                'percentage' => $percentage,
                'eligibleForCertificate' => $allCompleted && $allPassed,
            ];
        }

        return view('student.dashboard', compact('enrollments', 'completions', 'submissions', 'progress'));
    }
}
