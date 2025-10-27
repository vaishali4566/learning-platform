<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCourseController extends Controller
{
    public function index()
    {
        return view('user.courses.index');
    }

    public function myCourses()
    {
        return view('user.courses.myCourses');
    }
}
