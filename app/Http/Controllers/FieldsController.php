<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Fields;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreFieldsPostRequest;
use Auth;

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
        if ($this->userDisable($current_user_status, 'create_Field')) {
            $fid = (int) $request['fid'];
            if ($request['type'] ===  'local') {
                $url = '';
            } else {
                $url = $request['url'];
            }
            $res_arr = Fields::create([
                    'label' => $request['label'],
                    'key' => $request['key'],
                    'params' => $request['params'],
                    'publish' => $request['publish'],
                    'field_type' => strtoupper($request['field_type']),
                    'user_id' => Auth::user()->id(),
            ]);
            if ($res_arr['fid'] != 0) {
                $f_data = Fields::select('path')->find($fid);
                $current_path = substr($f_data['path'], 0, strrpos($f_data['path'], '|')) . '|' . $res_arr['id'] . '|' . $res_arr['sort'];
            } else
                $current_path = '0|'.$res_arr['id'].'|'.$res_arr['sort'];
            Fields::where('id',$res_arr['id'])->update(['path' => $current_path]);
            $request->session()->flash('success', trans('validation.user.successful'));
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
        $this->validate($request, ['key' => 'required|max:255|unique:fields,key,'.$request['key'].',id']);
        $check = $this->userDisable(Auth::user()->status, 'edit');
        if($check) {

        }else{

        }
        return Redirect::to('/menu');
    }
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
