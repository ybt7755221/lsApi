<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

use App\Http\Requests;

class ContentController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $contentObj = Content::where('state', 1)->orderBy('updated_at')->with(['users'])->get(['title','thumb','user_id','comment_status','state','updated_at']);
        return view('content/index', ['contentObj' => $contentObj]);
    }
}
