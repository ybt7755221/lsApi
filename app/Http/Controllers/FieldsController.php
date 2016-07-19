<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Fields;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreFieldsPostRequest;
use Auth;
use Illuminate\Support\Facades\Session;

class FieldsController extends Controller
{
    /**
     * Restful Api Function - Show the field for link.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        if( $this->userDisable($this->user_for_api->status, 'create') ){
            $fieldsObj = Fields::all(['id', 'label', 'key', 'params', 'publish', 'field_type']);
            if($fieldsObj){
                return $this->successRes($fieldsObj);
            }
            return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'Create']));
        }
    }

    /**
     * Restful Api Function - Create a data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        if( $this->userDisable($this->user_for_api->status, 'create') ){
            $record = Fields::create([
                    'label' => $request['label'],
                    'key' => $request['key'],
                    'params' => $request['params'],
                    'publish' => $request['publish'],
                    'field_type' => strtoupper($request['field_type']),
            ]);
            if($record){
                return $this->successRes($record);
            }else{
                return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
            }
        }
        return $this->errorRes(trans('validation.user.disabled_power',['op' => 'create']));
    }

    /**
     * Restful Api Function - Update a data.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        if( $this->userDisable($this->user_for_api->status, 'update') ){
            $create_data = [];
            if(isset($request['label']) && !empty($request['label'])){
                $create_data['label'] = $request['label'];
            }
            if(isset($request['key']) && !empty($request['key'])){
                $create_data['key'] = $request['key'];
            }
            if(isset($request['params']) && !empty($request['params'])){
                $create_data['params'] = $request['params'];
            }
            if(isset($request['publish']) && !empty($request['publish'])){
                $create_data['publish'] = $request['publish'];
            }
            if(isset($request['field_type']) && !empty($request['field_type'])){
                $create_data['field_type'] = $request['field_type'];
            }
            $result = Fields::find((int) $id)->update($create_data);
            if ( $result ) {
                $field = Fields::find((int) $id);
                return $this->successRes($field);
            }else{
                return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
            }
        }
        return $this->errorRes(trans('validation.user.disabled_power',['op' => 'update']));
    }

    /**
     * Restful Api Function - Removed a data.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id){
        if( $this->userDisable($this->user_for_api->status, 'delete') ){
            $record = Fields::destroy($id);
            if($record){
                return $this->successRes(trans('validation.user.successful'));
            }else{
                return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
            }
        }
        return $this->errorRes(trans('validation.user.disabled_power',['op' => 'update']));
    }
    /**
     * create a fields.
     *
     * @param StoreFieldsPostRequest $request
     * @return mixed
     */
    public function create(StoreFieldsPostRequest $request) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'create_field')) {
            $res_arr = Fields::create([
                    'label' => $request['label'],
                    'key' => $request['key'],
                    'params' => $request['params'],
                    'publish' => $request['publish'],
                    'field_type' => strtoupper($request['field_type']),
            ]);
            if($res_arr) {
                $request->session()->flash('success', trans('validation.user.successful'));
            }else{
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else{
            $request->session()->flash('error', trans('validation.user.disabled_power',['op' => 'create']));
        }
        return Redirect::to('/');
    }
    /**
     * Update a record.
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        if ( $this->userDisable(Auth::user()->status, 'edit') ) {
            $create_data = [
                    'label' => $request['label'],
                    'key' => $request['key'],
                    'params' => $request['params'],
                    'publish' => $request['publish'],
                    'field_type' => strtoupper($request['field_type']),
            ];
            $result = Fields::find((int)$request['id'])->update($create_data);
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
     * Remove a record.
     *
     * @param Request $request
     * @return string
     */
    public function removed(Request $request) {
        if ( $this->userDisable(Auth::user()->status, 'remove') ) {
            $result = Fields::destroy((int)$request['id']);
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
