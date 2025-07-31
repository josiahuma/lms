<?php

use App\Http\Controllers\Instructor\InstructorController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\LessonCompletionController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseController::class, 'landing'])->name('home');

Route::get('/courses/search', function () {
    return redirect()->route('courses.index');
})->name('courses.search');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return view('admin.dashboard');
    } else {
        abort(403, 'Access denied');
    }
})->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {

    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Course & Lesson Management
    Route::resource('courses', CourseController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('lessons', LessonController::class)->only(['edit', 'update', 'destroy']);
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('student.courses');
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
    Route::get('/courses/{course}/students', [CourseController::class, 'students'])->name('courses.students');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [LessonCompletionController::class, 'store'])->name('lessons.complete');

    // Quiz Management (Create/Edit/Delete)
    Route::get('/lessons/{lesson}/quiz/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/lessons/{lesson}/quiz/create', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'view'])->name('quizzes.view');
    Route::delete('/questions/{question}', [QuizQuestionController::class, 'destroy'])->name('questions.destroy');

    // ✅ Paginated Quiz Flow (only use these, remove old quiz routes)
    Route::get('/lessons/{lesson}/quiz/take', [QuizController::class, 'takePaginated'])->name('lessons.quiz.paginated');
    Route::post('/lessons/{lesson}/quiz/take', [QuizController::class, 'storePaginatedAnswer'])->name('lessons.quiz.paginated.store');
    Route::get('/lessons/{lesson}/quiz/submit', [QuizController::class, 'submitPaginated'])->name('lessons.quiz.paginated.submit');

    // ✅ Quiz Result Page
    Route::get('/lessons/{lesson}/quiz/result', [QuizController::class, 'result'])->name('lessons.quiz.result');

    // Student Dashboard
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

    // Certificate Download
    Route::get('/certificate/{course}', [\App\Http\Controllers\CertificateController::class, 'download'])
    ->middleware('auth')
    ->name('certificate.download');



});

require __DIR__ . '/auth.php';
