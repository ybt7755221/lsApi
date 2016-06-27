<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ContentController extends Controller
{
    private $thumb = '/images/default.png';
    private $base_path = '/uploads';
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $contentObj = Content::select('id', 'title', 'cat_id','user_id','comment_status','state','updated_at')
                    ->where('state', 1)
                    ->orderBy('updated_at')
                    ->with([
                    'users' => function($query){
                        $query->select('id','name');
                    },
                    'category' => function($query){
                        $query->select('id', 'cat_name');
                    }
                    ])
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
     * Delete a record
     *
     * @param Request $request
     */
    public function remove(Request $request){
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
        return json_encode($record);
    }
    /**
     * Create a data.
     *
     * @param Request $request
     * @return string
     */
    public function create(Request $request) {
        $request->session()->flash('op', 'create');
        $this->validate($request, ['title'=>'required|min:4|max:120|unique:content', 'body'=>'required']);
        if(isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
            $img_res = $this->uploadImage($this->base_path);
            if ($img_res['success'] == 0) {
                $request->session()->flash('error', $img_res['result']);
                return Redirect::to('/content');
            }
            $this->thumb = $img_res['result'];
        }
        $record = Content::create([
            'title' => $request['title'],
            'thumb' => $this->thumb,
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
     * Edit a record.
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        $request->session()->flash('op', 'edit');
        $request->session()->flash('edit_id', $request['id']);
        $this->validate($request, ['id'=>'required', 'title'=>'required|min:4|max:120|unique:content,title,'.$request['id'], 'body'=>'required']);
        $update_arr = [
            'title' => $request['title'],
            'body' => $request['body'],
            'comment_status' => $request['comment_status'],
            'state' => $request['state'],
            'cat_id' => $request['cat_id'],
        ];
        if(isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
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
            $record = ['success'=>0, 'result'=>trans('errors.LS40401_UNKNOWN')];
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

    /**
     * Upload a image for content.
     *
     * @return array
     */
    private function uploadImage($base_path) {
        $record = ['success' => 1];
        $microtime = microtime(true);
        $target_dir = $base_path.date('/Y-m/', $microtime);
        $filename = explode('.', basename($_FILES["thumb"]["name"]));
        $target_name = 'LS'.str_replace('.', '_', $microtime).'.'.end($filename);
        $imageFileType = substr($_FILES['thumb']['type'], stripos($_FILES['thumb']['type'], '/')+1);
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            if ($record['success'] === 0)
                $record['result'] += '<br />'.trans('validation.format_error');
            else
                $record = ['success' => 0, 'result' => trans('validation.format_error')];
        }
        if($_FILES['thumb']['size'] > 5*1000*1000) {
            if ($record['success'] === 0)
                $record['result'] += '<br />'.trans('validation.so_big');
            else
                $record = ['success' => 0, 'result' => trans('validation.so_big')];
        }
        if ($record['success'] === 1) {
            $mk_res = true;
            if(!is_dir(storage_path().$target_dir)) {
               $mk_res = @mkdir(storage_path().$target_dir, 0755, true);
            }
            if ($mk_res) {
                if (move_uploaded_file($_FILES["thumb"]["tmp_name"], storage_path().$target_dir . $target_name)) {
                    $record['result'] = '/storage' . $target_dir . $target_name;
                } else {
                    $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
                }
            }else{
                $record = ['success' => 0, 'result' => trans('errors.LS40301_INFO')];
            }
        }
        return $record;
    }
}
