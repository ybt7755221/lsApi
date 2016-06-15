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
        $categoryObj = Category::where('fid', 0)->orderBy('path', 'ASC')->orderBy('sort', 'ASC')->get();
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
            $res_arr = Category::create([
                'fid' => $fid,
                'cat_name' => $request['cat_name'],
                'description' => 'No Description for'.$request['cat_name'],
                'cat_image' => '/default.jpg',
                'sort' => 1,
                'display' => $request['display'],
                'type' => $request['type'],
                'url' => $request['url'],
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

    public function disabled(Request $request) {
        $record = $this->ableData($request);
        return json_encode($record);
    }

    public function removed(Request $request) {
        $record = $this->removeData((int)$request['id']);
        return json_encode($record);
    }

    /**
     * Disable a category.
     *
     * @param $id
     * @return array
     */
    private function ableData($request) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'display')) {
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
     * @param $id
     * @return array
     */
    private function removeData($id) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'remove')) {
            $res = Category::destroy((int)$id);
            if ($res) {
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
