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
        $linkObj = Link::all(['id', 'title', 'url', 'thumb', 'status', 'description']);
        $fieldsObj = Fields::all(['id', 'label', 'key', 'params', 'publish', 'field_type']);
        $serverArr = [
            'db_info' => $_SERVER['DB_CONNECTION'].' / '.$_SERVER['DB_COLLATION'],
            'cache_session_driver' => $_SERVER['CACHE_DRIVER'].' / '.$_SERVER['SESSION_DRIVER'],
            'http_accept' => explode(';', $_SERVER['HTTP_ACCEPT'])[0],
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'request_time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
        ];
        
        return view('home/home',['fieldsObj' => $fieldsObj, 'serverArr' => $serverArr, 'linkObj' => $linkObj, 'n' => 0]);
    }
}
