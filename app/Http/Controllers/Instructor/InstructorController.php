<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $courses = Auth::user()->courses()->with('lessons')->get();

        return view('instructor.dashboard', compact('courses'));
    }
}
