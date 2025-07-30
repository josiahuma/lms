<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function myCourses()
    {
        $courses = Auth::user()->enrolledCourses()->with('instructor')->get();
        return view('student.my-courses', compact('courses'));
    }

}
