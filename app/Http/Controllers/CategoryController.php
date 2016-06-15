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

    public function disabled() {

    }

    public function removed() {

    }

    private function disableData($id) {
        $res = Category::where('id',$id)->update(['display' => 'hidden']);
        if ( $res ) {
            
        }
    }
    private function removeData($id) {

    }
}
