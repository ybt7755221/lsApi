<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Link;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreLinkPostRequest;
use Auth;
use Illuminate\Support\Facades\Session;

class LinkController extends Controller
{
    /**
     * Create a Link data
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request) {
        if ( !$this->checkHTML($request['html_id']) ) {
            Session::flash('success', trans('errors.LS40401_UNKNOWN'));
            return Redirect::to('/');
        }
        if ( $this->userDisable(Auth::user()->status, 'edit') ) {
            $create_data = ['title' => $request['title'], 'url' => $request['url'], 'status' => $request['status'], 'description' => $request['description'], 'thumb' => '/storage/uploads/default.png'];
            if(!empty($_FILES['thumb']['name'])){
                $img_res = $this->uploadImage($this->base_path);
                if($img_res['success'] == 1) {
                    $create_data['thumb'] = $img_res['result'];
                }
            }
            $result = Link::create($create_data);
            if ( $result ) {
                $record = ['success' => 1, 'result' => trans('validation.user.successful',['name' => 'Data'])];
            }else{
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else{
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power'),['op' => 'Edit']];
        }
        $request->session()->flash($record['success'] == 1 ? 'success' : 'error', $record['result']);
        return Redirect::to('/');
    }
    /**
     * Edit a link data
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        if ( !$this->checkHTML($request['html_id']) ) {
            Session::flash('success', trans('errors.LS40401_UNKNOWN'));
            return Redirect::to('/');
        }
        if ( $this->userDisable(Auth::user()->status, 'edit') ) {
            $update_data = ['title' => $request['title'], 'url' => $request['url'], 'status' => $request['status'], 'description' => $request['description']];
            if(!empty($_FILES['thumb']['name'])){
                $img_res = $this->uploadImage($this->base_path);
                if($img_res['success'] == 1) {
                    $update_data['thumb'] = $img_res['result'];
                }
            }
            $result = Link::find((int)$request['id'])->update($update_data);
            if ( $result ) {
                $record = ['success' => 1, 'result' => trans('validation.user.successful',['name' => 'Data'])];
            }else{
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else{
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power'),['op' => 'Edit']];
        }
        $request->session()->flash($record['success'] == 1 ? 'success' : 'error', $record['result']);
        return Redirect::to('/');
    }
    /**
     * Removed a link data
     *
     * @param Request $request
     * @return string
     */
    public function removed(Request $request) {
        if ( !$this->checkHTML($request['html_id']) ) {
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            return json_encode($record);
        }
        if ( $this->userDisable(Auth::user()->status, 'remove') ) {
            $result = Link::destroy((int)$request['id']);
            if ( $result ) {
                $record = ['success' => 1, 'result' => trans('validation.user.remove_success',['name' => 'Data'])];
            }else{
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else{
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power'),['op' => 'Remove']];
        }
        return json_encode($record);
    }
    /**
     * Ensure can through this function.
     *
     * @param $html
     * @return bool
     */
    private function checkHTML($html) {
        if(!empty($html) && $html == 'link')
            return true;
        return false;
    }
}
