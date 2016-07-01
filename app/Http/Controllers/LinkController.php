<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Link;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreLinkPostRequest;
use Auth;

class LinkController extends Controller
{
    /**
     * Create a Link data
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request) {
        if ( $this->userDisable(Auth::user()->status, 'edit') ) {
            $create_data = ['title' => $request['title'], 'url' => $request['url'], 'status' => $request['status'], 'description' => $request['description']];
            if(!empty($_FILES['thumb']['name'])){
                $img_res = $this->uploadImage($this->base_path);
                if($img_res['success'] == 1) {
                    $create_data['thumb'] = $img_res['result'];
                }else{
                    $create_data['thumb'] = '/storage/uploads/default.png';
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
}
