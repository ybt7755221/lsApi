<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $userObj = User::all(['id', 'name', 'email', 'status', 'created_at', 'updated_at']);
        return view('users/index',['userObj'=>$userObj]);
    }
}
