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

        // Check lesson completion
        $totalLessons = $course->lessons->count();
        $completedLessons = $user->completedLessons()
            ->whereIn('lessons.id', $course->lessons->pluck('id'))
            ->count();

        // Check quiz scores
        $allPassed = true;
        foreach ($course->lessons as $lesson) {
            if ($lesson->quiz) {
                $submission = $lesson->quiz->submissions()
                    ->where('user_id', $user->id)
                    ->latest()
                    ->first();

                $score = $submission->score ?? 0;

                // ❗ No need to divide — score is already percentage
                if ($score < 80) {
                    $allPassed = false;
                    break;
                }
            }
        }

        // Final check
        if ($totalLessons === 0 || $completedLessons < $totalLessons || !$allPassed) {
            return redirect()->back()->with('error', 'You are not yet eligible for a certificate.');
        }

        $pdf = Pdf::loadView('certificates.certificate', compact('user', 'course'));
        return $pdf->download("certificate-{$course->id}.pdf");
    }

}
