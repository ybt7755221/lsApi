<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests;

class ContentController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $contentObj = Content::where('state', 1)
                    ->select('id', 'title','thumb','user_id','comment_status','state','updated_at')
                    ->orderBy('updated_at')
                    ->with(['users' => function($query){
                        $query->select('id','name');
                    }])
                    ->get();
        $categoryObj = Category::select('id', 'cat_name', 'path')->orderBy('path')->get();
        foreach($categoryObj as $category) {
            $count = substr_count($category->path, '|') - 2;
            if ($count > 0) {
                $symbol = '&nbsp;&nbsp;';
                for ($i = 0; $i < $count; $i++) {
                    $symbol .= "&nbsp;&nbsp;";
                }
                $category->cat_name = $symbol.$category->cat_name;
            }
        }
        return view('content/index', ['contentObj' => $contentObj, 'categoryObj' => $categoryObj]);
    }
}
