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

        // Fetch enrollments with related course and lessons
        $enrollments = $user->enrollments()->with('course.lessons.quiz.questions')->get();

        // Get completed lesson IDs for this user
        $completions = LessonCompletion::where('user_id', $user->id)->pluck('lesson_id')->toArray();

        // Get latest quiz submissions per quiz
        $submissions = QuizSubmission::where('user_id', $user->id)
        ->select(DB::raw('MAX(score) as score, quiz_id'))
        ->groupBy('quiz_id')
        ->get()
        ->keyBy('quiz_id');

        return view('student.dashboard', compact('enrollments', 'completions', 'submissions'));
    }
}
