<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download($courseId)
    {
        $user = Auth::user();
        $course = Course::with('lessons.quiz.questions')->findOrFail($courseId);

        // Check if user is eligible
        $totalLessons = $course->lessons->count();
        $completedLessons = $user->completedLessons()->whereIn('lessons.id', $course->lessons->pluck('id'))->count();

        $allPassed = true;
        foreach ($course->lessons as $lesson) {
            if ($lesson->quiz) {
                $maxScore = $lesson->quiz->questions->count();
                $submission = $lesson->quiz->submissions()->where('user_id', $user->id)->latest()->first();
                $userScore = $submission->score ?? 0;

                if ($maxScore == 0 || ($userScore / $maxScore) < 0.8) {
                    $allPassed = false;
                    break;
                }
            }
        }

        if ($totalLessons === 0 || $completedLessons < $totalLessons || !$allPassed) {
            return redirect()->back()->with('error', 'You are not yet eligible for a certificate.');
        }

        $pdf = Pdf::loadView('certificates.certificate', compact('user', 'course'));
        return $pdf->download("certificate-{$course->id}.pdf");
    }
}
