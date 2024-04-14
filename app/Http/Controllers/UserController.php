<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
       $users = User::orderBy('created_at', 'desc')->paginate(20);

       return view('users.index', ['users' => $users]);
    }
}
