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
            if ($request['type'] ===  'local')
                $url = '';
            else {
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
        return Redirect::to('/menu');
    }

    /**
     * Update a record.
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        $this->validate($request, ['cat_name' => 'required|max:255|unique:fields,cat_name,'.$request['id'].',id']);
        $check = $this->userDisable(Auth::user()->status, 'edit');
        if($check) {
            if ($request['type'] ===  'local')
                $url = '';
            else {
                $url = $request['url'];
            }
            $fields = Fields::find($request['id']);
            if ($request['fid'] !== $fields['fid']) {
                if($fields['fid'] != 0) {
                    $f_data = Fields::select('path')->find($request['fid']);
                    $new_path = substr($f_data['path'], 0, strrpos($f_data['path'], '|')) . '|' . $fields['id'] . '|' . $fields['sort'];
                }else{
                    $new_path = '0|'.$fields['id'].'|'.$fields['sort'];
                }
            }
            $fields->cat_name = $request['cat_name'];
            $fields->fid = $request['fid'];
            $fields->type = $request['type'];
            $fields->url = $url;
            $fields->display = $request['display'];
            if (isset($new_path)) {
                $fields->path = $new_path;
            }else{
                $fields->path = '';
            }
            $fields->save();
        }else{

        }
        return Redirect::to('/menu');
    }
    /**
     * operatiion multiple data.
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
                    $record = $this->removeData($id);
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
     * disabled and enabled a fields
     *
     * @param Request $request
     * @return string
     */
    public function disabled(Request $request) {
        $record = $this->ableData($request);
        return json_encode($record);
    }
    /**
     * removed a fields
     *
     * @param Request $request
     * @return string
     */
    public function removed(Request $request) {
        $record = $this->removeData((int)$request['id']);
        return json_encode($record);
    }
    public function subMenu(Request $request){
        $check = $this->userDisable(Auth::user()->status, 'edit');
        if ($check) {
            $record['result'] = Fields::where('fid',$request['id']*1)->get();
            if(count($record['result']) > 0) {
                $record['success'] = 1;
            } else {
                $record = ['success' => 0, 'result' => trans('system.NO_DATA')];
            }
        }else{
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return json_encode($record);
    }
    /**
     * Disable a fields.
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
            $res = Fields::where('id',(int)$request['id'])->update(['display' => $display_str]);
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
     * Remove a fields
     *
     * @param $id
     * @return array
     */
    private function removeData($id) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'remove')) {
            $id = (int)$id;
            $res = Fields::destroy($id);
            if ($res) {
                $result = Fields::where('fid', $id)->delete();
                $record['success'] = 1;
                $record['result'] = trans('validation.user.remove_success', ['name' => 'fields']);
            } else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }else {
            $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
        }
        return $record;
    }
}
