<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.dashboard');
    }
}