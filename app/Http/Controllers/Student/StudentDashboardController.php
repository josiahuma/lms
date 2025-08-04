<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LessonCompletion;
use App\Models\QuizSubmission;
use Illuminate\Support\Facades\DB;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()->with('course.lessons.quiz.questions')->get();
        $completions = LessonCompletion::where('user_id', $user->id)->pluck('lesson_id')->toArray();

        $submissions = QuizSubmission::where('user_id', $user->id)
            ->selectRaw('MAX(score) as score, MAX(correct_answers) as correct_answers, quiz_id')
            ->groupBy('quiz_id')
            ->get()
            ->keyBy('quiz_id');

        $progress = [];

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $lessons = $course->lessons;
            $totalLessons = $lessons->count();
            $completedLessons = collect($lessons)->whereIn('id', $completions)->count();

            // Check quiz pass status
            $allPassed = true;

            foreach ($lessons as $lesson) {
                if ($lesson->quiz) {
                    $quiz = $lesson->quiz;
                    $score = $submissions[$quiz->id]->score ?? 0;

                    if ($score < 80) {
                        $allPassed = false;
                        break;
                    }
                }
            }

            $progress[$course->id] = [
                'completedLessons' => $completedLessons,
                'totalLessons' => $totalLessons,
                'eligibleForCertificate' => $totalLessons > 0 && $completedLessons === $totalLessons && $allPassed,
            ];
        }

        return view('student.dashboard', compact('enrollments', 'completions', 'submissions', 'progress'));
    }

}
