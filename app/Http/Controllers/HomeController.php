<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Fields;
use App\Models\Link;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $linkObj = Link::all(['id', 'title', 'status']);
        $fieldsObj = Fields::all(['id', 'label', 'params', 'publish', 'field_type']);
        $serverArr = [
            'db_info' => $_SERVER['DB_CONNECTION'].' / '.$_SERVER['DB_COLLATION'],
            'cache_session_driver' => $_SERVER['CACHE_DRIVER'].' / '.$_SERVER['SESSION_DRIVER'],
            'http_accept' => explode(';', $_SERVER['HTTP_ACCEPT'])[0],
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'request_time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
        ];
        return view('home/home',['fieldsObj' => $fieldsObj, 'serverArr' => $serverArr, 'linkObj' => $linkObj, 'n' => 0]);
    }

    public function error()
    {
        return view('errors/503')->with('errorInfo', ['errorCode' => trans('errors.LS40301_NAME'), 'errorMsg' => trans('errors.LS40301_INFO')]);
    }
}
