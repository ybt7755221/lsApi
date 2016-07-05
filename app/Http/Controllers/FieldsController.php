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
