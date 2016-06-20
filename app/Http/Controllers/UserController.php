<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Http\Requests\StoreUserPostRequest;
use Redirect;
use Auth;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userObj = User::limit(20)->orderBy('id', 'desc')->get(['id', 'name', 'email', 'password', 'status', 'created_at', 'updated_at']);
        return view('users/index', ['userObj' => $userObj]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(StoreUserPostRequest $request)
    {
        $current_user_status = Auth::user()->status;
        if ($current_user_status === 0) {
            $request->session()->flash('waring', trans('validation.user.disabled_power',['op' => 'create']));
            return Redirect::to('user');
        }
        if ($request['status'] == 4) {
            $status = 0;
        } else {
            $status = $request['status'];
        }
        User::create(['name' => $request['name'], 'email' => $request['email'], 'password' => bcrypt($request['password']), 'status' => $status]);
        $request->session()->flash('success', trans('validation.user.create_success'));
        return Redirect::to('user');
    }

    /**
     * Edit a user.
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request) {
        $current_user_status = Auth::user()->status;
        $user = User::find($request['id']);
        if ($current_user_status === 0) {
            $request->session()->flash('waring', trans('validation.user.disabled_power',['op' => 'edit']));
            return Redirect::to('user');
        }
        if ($current_user_status < $user['status']) {
            $request->session()->flash('waring', trans('validation.user.disabled_level'));
            return Redirect::to('user');
        }
        $this->validate($request, ['name' => 'required|max:255', 'email' => 'required|email|max:255|unique:users,email,'.$user->id, 'password' => 'min:6|confirmed']);
        if (!empty($request['password'])) {
            $user->password = bcrypt($request['password']);
        }
        $user->name = $request['name'];
        $user->email = $request['email'];
        if ( $user['status'] == 4)
            $user->status = 4;
        else
            $user->status = $request['status'] * 1;
        $user->updated_at = $_SERVER['REQUEST_TIME'];
        $result = $user->save();
        if ( !$result ) {
            $request->session()->flash('error', trans('errors.LS40401_UNKNOWN'));
        } else {
            $request->session()->flash('success', trans('validation.user.edit_success'));
        }
        return Redirect::to('user');
    }
    /**
     * Remove the user.
     *
     * @param Request $request
     * @return string
     */
    public function remove(Request $request)
    {
        $current_user_status = Auth::user()->status;
        $id = $request['id'] * 1;
        $record = $this->removeData($current_user_status, $id);
        return json_encode($record);
    }
    /**
     * Disabled a user.
     *
     * @param Request $request
     * @return string
     */
    public function disable(Request $request) {
        $current_user_status = Auth::user()->status;
        $id = $request['id'] * 1;
        $record = $this->disableData($current_user_status, $id);
        return json_encode($record);
    }
    public function multiOperation(){
        $user_id = Auth::user()->id;
        $current_user_status = Auth::user()->status;
        $res = ['success' => -2, 'result' => ''];
        switch ($_POST['op']) {
            case 'disable' :
                foreach($_POST['ids'] as $id) {
                    if ($user_id != $id) {
                        $record = $this->disableData($current_user_status, $id);
                        if ($record['success'] !== 1) {
                            $res['success'] = 0;
                            $res['result'] .= $record['result'] . "</br>";
                        }
                    }else{
                        $res['success'] = 0;
                        $res['result'] .= trans('validation.user.operation_self',['op'=>'disable']) . "</br>";
                    }
                }
                if ($res['success'] === -2){
                    $res = ['success' => 1, 'result' => trans('validation.user.disable_success', ['name' => 'multiple data'])];
                }
                break;
            case 'remove' :
                foreach($_POST['ids'] as $id) {
                    if ($user_id != $id) {
                        $record = $this->removeData($current_user_status, $id);
                        if ($record['success'] !== 1) {
                            $res['success'] = 0;
                            $res['result'] .= $record['result'] . "</br>";
                        }
                    }else{
                        $res['success'] = 0;
                        $res['result'] .= trans('validation.user.operation_self',['op'=>'remove']) . "</br>";
                    }
                }
                if ($res['success'] === -2){
                    $res = ['success' => 1, 'result' => trans('validation.user.remove_success', ['name' => 'multiple data'])];
                }
                break;
            default :
                $res = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
                break;
        }
        return json_encode($res);
    }
    /**
     * check user powerful for remove.
     *
     * @param $current_user_status
     * @param $state
     * @param $op
     * @return array
     */
    protected function Powerful($current_user_status, $state, $op)
    {
        if ($current_user_status < 2) {
            $record = ['success' => 0, 'result' => trans('validation.user.disabled_power', ['op' => $op])];
        } elseif ($state === 4) {
            $record = ['success' => 0, 'result' => trans('validation.user.remove_super')];
        } else {
            $record = ['success' => 1];
        }
        return $record;
    }

    /**
     * the detail function for disable a data.
     *
     * @param $current_user_status
     * @param $id
     * @return array
     */
    private function disableData($current_user_status, $id){
        $user = User::find($id);
        $record = $this->Powerful($current_user_status, $user['status'],'disable');
        if ($record['success'] === 1) {
            if($user['status'] === 0) {
                $record = ['success' => 0, 'result' => trans('validation.user.disabled_again')];
            } else {
                $user->status = 0;
                $result = $user->save();
                if ( $result ) {
                    $record['result'] = trans('validation.user.disable_success', ['name' => $user['name']]);
                }else {
                    $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
                }
            }
        }
        return $record;
    }

    /**
     * the detail function for Delete a data.
     *
     * @param $current_user_status
     * @param $id
     * @return array
     */
    private function removeData($current_user_status, $id){
        $user = User::find($id);
        $record = $this->Powerful($current_user_status, $user['status'],'remove');
        if ($record['success'] === 1) {
            $result = $user->delete();
            if ( $result && $current_user_status === $user['status'] ) {
                Redirect::to('login');
            }elseif ( $result ) {
                $record['result'] = trans('validation.user.remove_success', ['name' => $user['name']]);
            }else {
                $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
            }
        }
        return $record;
    }
}
