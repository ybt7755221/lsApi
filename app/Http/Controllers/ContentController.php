<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

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
        $categoryObj = Category::where('type', '!=', 'link')->select('id', 'cat_name', 'path')->orderBy('path')->get();
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

    /**
     * Create a data.
     *
     * @param Request $request
     * @return string
     */
    public function create(Request $request) {
        $this->validate($request, ['title'=>'required|min:4|max:120|unique:content', 'body'=>'required']);
        $record = Content::create([
            'title' => $request['title'],
            'user_id' => Auth::user()->id,
            'body' => $request['body'],
            'comment_status' => $request['comment_status'],
            'state' => $request['state'],
            'cat_id' => $request['cat_id'],
        ]);
        if ($record) {
            //$record = json_encode(['success' => 1, 'result' => $record]);
            $request->session()->flash('success', trans('validation.user.successful'));
        } else {
            $request->session()->flash('error', trans('errors.LS40401_UNKNOWN'));
        }
        return Redirect::to('/content');
    }

    /**
     * Remove a data.
     *
     * @param Request $request
     * @return string
     */
    public function removed(Request $request) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'remove')) {
            $id = (int)$request['id'];
            $res = Content::destroy($id);
            if ($res) {
                $record['success'] = 1;
                $record['result'] = trans('validation.user.remove_success', ['name' => 'category']);
            } else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else {
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return json_encode($record);
    }
    public function getOldData(Request $request) {
        $id = (int)$request['id'];
        $res = Content::find($id);
        if ($res) {
            $record['success'] = 1;
            $record['result'] = $res;
        } else {
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return json_encode($record);
    }
}
