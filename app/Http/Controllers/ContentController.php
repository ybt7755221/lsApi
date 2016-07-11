<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ContentController extends Controller
{
    private $thumb = '/storage/uploads/default.png';
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (isset($_GET['state']) && $_GET['state'] != '' && $_GET['state'] != -1 ){
            $contentObj = Content::select('id', 'title', 'cat_id', 'user_id', 'comment_status', 'state', 'updated_at')->where('state', (int)$_GET['state'])->orderBy('updated_at')->with(['users' => function ($query) {
                $query->select('id', 'name');
            }, 'category' => function ($query) {
                $query->select('id', 'cat_name');
            }])->limit($this->pagination_number)->get();
        } else {
            $contentObj = Content::select('id', 'title', 'cat_id', 'user_id', 'comment_status', 'state', 'updated_at')->orderBy('updated_at')->with(['users' => function ($query) {
                        $query->select('id', 'name');
                    }, 'category' => function ($query) {
                        $query->select('id', 'cat_name');
                    }])->limit($this->pagination_number)->get();
        }
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
        if(isset($_GET['state'])) {
            if( ($_GET['state'] == 0 || !empty($_GET['state'])) ){
                Session::put('content_state', $_GET['state']);
            }
        }else{
            Session::forget('content_state');
        }
        //$this->isRestApi($contentObj);
        return view('content/index', ['contentObj' => $contentObj, 'categoryObj' => $categoryObj]);
    }
    public function pagination() {
        $result = ['success' => 0];
        if($_GET['paging']){
            switch ($_GET['paging']) {
                case 'next' :
                    $id = isset($_GET['start_id']) && !empty($_GET['start_id']) ? (int)$_GET['start_id'] : 0;
                    $symbol = '>';
                    break;
                case 'prev' :
                    $id = isset($_GET['last_id']) && !empty($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
                    $symbol = '<';
                    break;
                default :
                    break;
            }
            if($id && $symbol) {
                $result['success'] = 1;
                $result['result'] = DB::table('content')
                        ->where('content.id', $symbol, $id)
                        ->leftJoin('users', 'users.id', '=', 'content.user_id')
                        ->leftJoin('category', 'category.id', '=', 'content.cat_id')
                        ->select('content.id', 'content.title', 'content.cat_id', 'content.user_id', 'content.comment_status', 'content.state', 'content.updated_at', 'category.cat_name', 'users.name')
                        ->orderBy('content.id','ASC')
                        ->limit($this->pagination_number)
                        ->get();
            }else{
                $result['result'] = trans('validator.user.lost_fields');
            }
        }
        return response()->json($result);

    }
    /**
     * Delete a record
     *
     * @param Request $request
     */
    public function remove(Request $request){
        $http = $_SERVER['HTTP_REFERER'];
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'remove')) {
            $id = (int) $request['id'];
            $res = Content::find($id)->update(['state'=>2]);
            if ($res) {
                $record['success'] = 1;
                $record['result'] = trans('validation.user.remove_success', ['name' => 'content']);
            } else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else {
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return response()->json($record);
    }
    /**
     * Create a data.
     *
     * @param Request $request
     * @return string
     */
    public function create(Request $request) {
        if ( $this->userDisable(Auth::user()->status, 'create') ) {
            $request->session()->flash('op', 'create');
            $this->validate($request, ['title' => 'required|min:4|max:120|unique:content', 'body' => 'required']);
            if (isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
                $img_res = $this->uploadImage($this->base_path);
                if ($img_res['success'] == 0) {
                    $request->session()->flash('error', $img_res['result']);
                    return Redirect::to('/content');
                }
                $this->thumb = $img_res['result'];
            }
            $record = Content::create(['title' => $request['title'], 'thumb' => $this->thumb, 'user_id' => Auth::user()->id, 'body' => $request['body'], 'comment_status' => $request['comment_status'], 'state' => $request['state'], 'cat_id' => $request['cat_id'],]);
            if ($record) {
                $request->session()->flash('success', trans('validation.user.successful'));
            } else {
                $request->session()->flash('error', trans('errors.LS40401_UNKNOWN'));
            }
        }else {
            $request->session()->flash('error', trans('errors.LS40401_UNKNOWN'));
        }
        response()->json(['success' => 1, 'result' => trans('validation.user.successful')]);
        return Redirect::to('/content');
    }
    /**
     * Edit a record.
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        $check = $this->userDisable(Auth::user()->status, 'edit');
        if($check) {
            $request->session()->flash('op', 'edit');
            $request->session()->flash('edit_id', $request['id']);
            $this->validate($request, ['id' => 'required', 'title' => 'required|min:4|max:120|unique:content,title,' . $request['id'], 'body' => 'required']);
            $update_arr = ['title' => $request['title'], 'body' => $request['body'], 'comment_status' => $request['comment_status'], 'state' => $request['state'], 'cat_id' => $request['cat_id']];
            if (isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
                $img_res = $this->uploadImage($this->base_path);
                if ($img_res['success'] == 0) {
                    $request->session()->flash('error', $img_res['result']);
                    return Redirect::to('/content');
                }
                $update_arr['thumb'] = $img_res['result'];
            }
            $check = Content::where('id', $request['id'])->update($update_arr);
            if ($check) {
                $record['success'] = 1;
                $record['result'] = $update_arr;
                $record['result']['user_id'] = $request['user_id'];
                $record['result']['updated_at'] = $_SERVER['REQUEST_TIME'];
            } else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else{
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return Redirect::to('/content');
    }

    /**
     * Get old content.
     *
     * @param Request $request
     * @return string
     */
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
