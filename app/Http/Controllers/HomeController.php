<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Fields;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Fields::all();
        return view('home',['fields' => $fields]);
    }

    public function error()
    {
        return view('errors/503')->with('errorInfo', ['errorCode' => trans('errors.LS40301_NAME'), 'errorMsg' => trans('errors.LS40301_INFO')]);
    }
}
