<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function sayHi()
    {
        return "hii";
    }
    public function userRegistrations(Request $request)
    {
        return "register successfully";
    }
    public function userLogin()
    {
        return "login successfully";
    }
    public function getAllUser()
    {
        return "all users";
    }
}
