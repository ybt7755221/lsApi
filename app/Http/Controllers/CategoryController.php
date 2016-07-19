<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreCategoryPostRequest;
use Auth;

class CategoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categoryObj = Category::where('fid', 0)->orderBy('id', 'DESC')->get();
        if($this->isRestApi()){
            return $this->successRes($categoryObj);
        }
        return view('category/index',['categoryObj' => $categoryObj]);
    }

    /**
     * create a category.
     *
     * @param StoreCategoryPostRequest $request
     * @return mixed
     */
    public function create(StoreCategoryPostRequest $request) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'create')) {
            $fid = (int) $request['fid'];
            if ($request['type'] ===  'local')
                $url = '';
            else {
                $url = $request['url'];
            }
            $res_arr = Category::create([
                'fid' => $fid,
                'cat_name' => $request['cat_name'],
                'description' => 'No Description for'.$request['cat_name'],
                'cat_image' => '/storage/uploads/default.png',
                'sort' => 1,
                'display' => $request['display'],
                'type' => $request['type'],
                'url' => $url,
                'created_at' => date('Y-m-d H:i:s', time()),
            ]);
            if ($res_arr['fid'] != 0) {
                $f_data = Category::select('path')->find($fid);
                $current_path = substr($f_data['path'], 0, strrpos($f_data['path'], '|')) . '|' . $res_arr['id'] . '|' . $res_arr['sort'];
            } else
                $current_path = '0|'.$res_arr['id'].'|'.$res_arr['sort'];
            Category::where('id',$res_arr['id'])->update(['path' => $current_path]);
            $request->session()->flash('success', trans('validation.user.successful'));
        }else{
            $request->session()->flash('error', trans('validation.user.disabled_power',['op' => 'create']));
        }
        return Redirect::to('/menu');
    }

    /**
     * Update a record.
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        $this->validate($request, ['cat_name' => 'required|max:255|unique:category,cat_name,'.$request['id'].',id']);
        $check = $this->userDisable(Auth::user()->status, 'edit');
        if($check) {
            $category = Category::find($request['id']);
            if(empty($category)){
                return $this->errorRes('Not Found the data that you would like to modified.');
            }
            if (isset($request['cat_name']) && !empty($request['cat_name']))
                $category->cat_name = $request['cat_name'];
            if (isset($request['fid'])){
                $category->fid = $request['fid'];
                if ($request['fid'] !== $category['fid']) {
                    if($category['fid'] != 0) {
                        $f_data = Category::select('path')->find($request['fid']);
                        $new_path = substr($f_data['path'], 0, strrpos($f_data['path'], '|')) . '|' . $category['id'] . '|' . $category['sort'];
                    }else{
                        $new_path = '0|'.$category['id'].'|'.$category['sort'];
                    }
                }
                if (isset($new_path)) {
                    $category->path = $new_path;
                }else{
                    $category->path = '';
                }
            }
            if (!empty($request['type']))
                $category->type = $request['type'];
            if (!empty($request['url'])){
                if ($request['type'] ===  'local')
                    $url = '';
                else {
                    $url = $request['url'];
                }
                $category->url = $url;
            }
            if (!empty($request['display']))
                $category->display = $request['display'];
            $category->save();
        }else{
            $request->session()->flash('error', trans('validation.user.disabled_power',['op' => 'eidt']));
        }
        return Redirect::to('/menu');
    }
    /**
     * Operation multiple data.
     *
     * @return string
     */
    public function multiOperation () {
        $res = ['success' => -2, 'result' => ''];
        switch ($_POST['op']) {
            case 'disabled' :
                foreach($_POST['ids'] as $id) {
                    $record = $this->ableData(['id' => $id, 'op'=>$_POST['op']]);
                    if ($record['success'] !== 1) {
                        $res['success'] = 0;
                        $res['result'] .= $_POST['result'] . "</br>";
                    }
                }
                if ($res['success'] === -2){
                    $res = ['success' => 1, 'result' => trans('validation.user.disable_success', ['name' => 'multiple data'])];
                }
                break;
            case 'enabled' :
                foreach($_POST['ids'] as $id) {
                    $record = $this->ableData(['id' => $id, 'op'=>$_POST['op']]);
                    if ($record['success'] !== 1) {
                        $res['success'] = 0;
                        $res['result'] .= $record['result'] . "</br>";
                    }
                }
                if ($res['success'] === -2){
                    $res = ['success' => 1, 'result' => trans('validation.user.disable_success', ['name' => 'multiple data'])];
                }
                break;
            case 'removed' :
                foreach($_POST['ids'] as $id) {
                    $record = $this->removeData(Auth::user()->status, $id);
                    if ($record['success'] !== 1) {
                        $res['success'] = 0;
                        $res['result'] .= $record['result'] . "</br>";
                    }
                }
                if ($res['success'] === -2){
                    $res = ['success' => 1, 'result' => trans('validation.user.disable_success', ['name' => 'multiple data'])];
                }
                break;
            default :
                $res = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
                break;
        }
        return json_encode($res);
    }
    /**
     * disabled and enabled a category
     *
     * @param Request $request
     * @return string
     */
    public function disabled(Request $request) {
        $record = $this->ableData($request);
        return response()->json($record);
    }
    /**
     * removed a category
     *
     * @param Request $request
     * @return string
     */
    public function removed(Request $request) {
        $record = $this->removeData(Auth::user()->status,(int)$request['id']);
        return response()->json($record);
    }

    /**
     * Get the child subMenu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subMenu(Request $request){
        $check = $this->userDisable(Auth::user()->status, 'edit');
        if ($check) {
           $record['result']  = Category::where('fid',$request['id']*1)->get();
            if(count($record['result']) > 0) {
                $record['success'] = 1;
            } else {
                $record = ['success' => 0, 'result' => trans('system.NO_DATA')];
            }
        }else{
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return response()->json($record);
    }
    /**
     * Restful Api Function - remove a data.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request,  $id) {
        $id = (int) $id;
        $record = $this->removeData((int) $this->user_for_api->status, $id);
        return response()->json($record);
    }
    public function update(Request $request, $id) {
        if(isset($this->user_for_api->status) && !empty($this->user_for_api->status)) {
            $status = (int) $this->user_for_api->status;
        }else{
            $status = 0;
        }
        $check = $this->userDisable($status, 'edit');
        if($check) {
            $category = Category::find($id);
            if(empty($category)){
                return $this->errorRes('Not Found the data that you would like to modified.');
            }
            if (isset($request['cat_name']) && !empty($request['cat_name']))
                $category->cat_name = $request['cat_name'];
            if (isset($request['fid'])){
                $category->fid = $request['fid'];
                if ($request['fid'] !== $category['fid']) {
                    if($category['fid'] != 0) {
                        $f_data = Category::select('path')->find($request['fid']);
                        $new_path = substr($f_data['path'], 0, strrpos($f_data['path'], '|')) . '|' . $category['id'] . '|' . $category['sort'];
                    }else{
                        $new_path = '0|'.$category['id'].'|'.$category['sort'];
                    }
                }
                if (isset($new_path)) {
                    $category->path = $new_path;
                }else{
                    $category->path = '';
                }
            }
            if (!empty($request['type']))
                $category->type = $request['type'];
            if (!empty($request['url'])){
                if ($request['type'] ===  'local')
                    $url = '';
                else {
                    $url = $request['url'];
                }
                $category->url = $url;
            }
            if (!empty($request['display']))
                $category->display = $request['display'];
            $category->save();
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'eidt']));
        }
        return $this->successRes($category);
    }
    /**
     * Restful Api Function - Create a category.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) {
        $current_user_status = $this->user_for_api->status;
        if ($this->userDisable($current_user_status, 'create')) {
            $fid = (int) $request['fid'];
            if ($request['type'] ===  'local')
                $url = '';
            else {
                $url = $request['url'];
            }
            $res_arr = Category::create([
                    'fid' => $fid,
                    'cat_name' => $request['cat_name'],
                    'description' => $request['description'] ? $request['description'] : 'No Description for'.$request['cat_name'],
                    'cat_image' => '/storage/uploads/default.png',
                    'sort' => $request['sort'] ? $request['sort'] : 1,
                    'display' => $request['display'],
                    'type' => $request['type'],
                    'url' => $url,
                    'created_at' => date('Y-m-d H:i:s', time()),
            ]);
            if ($res_arr['fid'] != 0) {
                $f_data = Category::select('path')->find($fid);
                $current_path = substr($f_data['path'], 0, strrpos($f_data['path'], '|')) . '|' . $res_arr['id'] . '|' . $res_arr['sort'];
            } else
                $current_path = '0|'.$res_arr['id'].'|'.$res_arr['sort'];
            Category::where('id',$res_arr['id'])->update(['path' => $current_path]);
            $res_arr['path'] = $current_path;
            if ($this->isRestApi()) {
                return $this->successRes($res_arr);
            }
            $request->session()->flash('success', trans('validation.user.successful'));
        }else{
            if($this->isRestApi())
                return $this->errorRes(trans('validation.user.disabled_power',['op' => 'create']));
            $request->session()->flash('error', trans('validation.user.disabled_power',['op' => 'create']));
        }
        return Redirect::to('/menu');
    }
    /**
     * Disable a category.
     *
     * @param $request
     * @return array
     */
    private function ableData($request) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'edit')) {
            if ( $request['op'] === 'disabled')
                $display_str = 'hidden';
            else if ( $request['op'] === 'enabled')
                $display_str = 'show';
            $res = Category::where('id',(int)$request['id'])->update(['display' => $display_str]);
            if ($res) {
                $record['success'] = 1;
                $record['result'] = trans('validation.user.successful');
            } else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else {
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power', ['op' => 'disabled'])];
        }
        return $record;
    }

    /**
     * Remove a category
     *
     * @param $current_user_status
     * @param $id
     * @return array
     */
    private function removeData($current_user_status, $id) {
        if ($this->userDisable($current_user_status, 'delete')) {
            $id = (int)$id;
            $res = Category::destroy($id);
            if ($res) {
                $result = Category::where('fid', $id)->delete();
                $record['success'] = 1;
                $record['result'] = trans('validation.user.remove_success', ['name' => 'category']);
            } else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else {
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return $record;
    }
}
