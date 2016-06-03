<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Http\Requests\StoreUserPostRequest;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userObj = User::limit(20)->orderBy('id', 'desc')->get(['id', 'name', 'email', 'status', 'created_at', 'updated_at']);
        return view('users/index', ['userObj' => $userObj]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(StoreUserPostRequest $request)
    {
        if ( $request['status'] == 4 ) {
            $status = 0;
        } else {
            $status = $request['status'];
        }
        $user = User::create(['name' => $request['name'], 'email' => $request['email'], 'password' => bcrypt($request['password']), 'status' => $status]);
        $request->session()->flash('success', 'Create new user was successful!');
        return Redirect::to('user');
    }
}
