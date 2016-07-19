<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreLinkPostRequest;
use Illuminate\Support\Facades\Session;

class LinkController extends Controller
{
    /**
     * Restful Api Function - Show the list for link.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        if( $this->userDisable($this->user_for_api->status, 'create') ){
            $linkObj = Link::all(['id', 'title', 'url', 'thumb', 'status', 'description']);
            if($linkObj){
                return $this->successRes($linkObj);
            }
            return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'Create']));
        }
    }

    /**
     * Restful Api Function - Create a Link data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        if( $this->userDisable($this->user_for_api->status, 'create') ){
            $create_data = ['title' => $request['title'], 'url' => $request['url'], 'status' => $request['status'], 'description' => $request['description'], 'thumb' => '/storage/uploads/default.png'];
            $result = Link::create($create_data);
            if ( $result ) {
                return $this->successRes(trans('validation.user.successful',['name' => 'Data']));
            }else{
                return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
            }
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'Create']));
        }
    }

    /**
     * Restful Api Function - update a data.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        if ( $this->userDisable($this->user_for_api->status, 'edit') ) {
            $update_data = ['title' => $request['title'], 'url' => $request['url'], 'status' => $request['status'], 'description' => $request['description']];
            $result = Link::find((int) $id)->update($update_data);
            if ( $result ) {
                $update_data['id'] = $id;
                return $this->successRes($update_data);
            }else{
                return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
            }
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'Edit']));
        }
    }

    /**
     * Restful Api Function - Delete a data.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id){
        if ( $this->userDisable($this->user_for_api->status, 'delete') && $this->isRestApi() ) {
            $record = Link::destroy($id);
            if($record){
                return $this->successRes(trans('validation.user.successful'));
            }
        }
        return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
    }
    /**
     * Create a Link data
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request) {
        if ( $this->userDisable(Auth::user()->status, 'create') ) {
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
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power',['op' => 'Edit'])];
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
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power',['op' => 'Edit'])];
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
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power',['op' => 'Remove'])];
        }
        return json_encode($record);
    }
}
