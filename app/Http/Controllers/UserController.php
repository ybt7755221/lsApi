<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Http\Requests\StoreUserPostRequest;
use Redirect;
use Cache;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userObj = $this->checkCache();
        if( $userObj == false || empty($userObj) ){
            $userObj = User::limit(20)->orderBy('id', 'desc')->get(['id', 'name', 'email', 'password', 'status', 'created_at', 'updated_at']);
            Cache::put($this->cache_key, $userObj, $this->cache_time);
        }
        if ( $this->isRestApi() ) {
            return $this->successRes($userObj);
        }
        return view('users/index', ['userObj' => $userObj]);
    }

    /**
     * Restful Api Function - Get a data by id.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        $userObj = $this->checkCache();
        if( $userObj == false || empty($userObj) ){
            $userObj = User::where('id', (int) $id)->first(['id', 'name', 'email', 'password', 'status', 'created_at', 'updated_at']);
            Cache::put($this->cache_key, $userObj, $this->cache_time);
        }
        return $this->successRes($userObj);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  StoreUserPostRequest $request
     * @return User
     */
    public function create(StoreUserPostRequest $request)
    {
        $current_user_status = $this->current_user->status;
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
        Cache::forget($this->cache_key);
        $request->session()->flash('success', trans('validation.user.create_success'));
        return Redirect::to('user');
    }

    /**
     * Edit a user.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, $id=-1) {
        $current_user_status = $this->current_user->status;
        $id = (int) $id == -1 ? (int) $request['id'] : (int) $id;
        $user = User::find($id);
        if ($current_user_status === 0) {
            if ($this->isRestApi()){
                return $this->errorRes(trans('validation.user.disabled_power',['op' => 'edit']));
            }
            $request->session()->flash('waring', trans('validation.user.disabled_power',['op' => 'edit']));
            return Redirect::to('user');
        }
        if ($current_user_status < $user['status']) {
            if ($this->isRestApi()){
                return $this->errorRes(trans('validation.user.disabled_level'));
            }
            $request->session()->flash('waring', trans('validation.user.disabled_level'));
            return Redirect::to('user');
        }
        if(!$this->isRestApi()) {
            $this->validate($request, ['name' => 'required|max:255', 'email' => 'required|email|max:255|unique:users,email,' . $user->id, 'password' => 'min:6|confirmed']);
        }
        if (!empty($request['password'])) {
            $user->password = bcrypt($request['password']);
        }
        if (!empty($request['name']))
            $user->name = $request['name'];
        if (!empty($request['email']))
            $user->email = $request['email'];
        if ( $user['status'] == 4)
            $user->status = 4;
        else{
            if($request['status'] == 4){
                if ($this->isRestApi()){
                    return $this->errorRes(trans('validation.user.disabled_power',['op' => 'edit']));
                }
                $request->session()->flash('waring', trans('validation.user.disabled_power',['op' => 'edit']));
                return Redirect::to('user');
            }
            $user->status = (int) $request['status'];
        }
        $user->updated_at = $_SERVER['REQUEST_TIME'];
        $result = $user->save();
        if ( !$result ) {
            if($this->isRestApi())
                return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
            $request->session()->flash('error', trans('errors.LS40401_UNKNOWN'));
        } else {
            if($this->checkCache()){
                Cache::forget($this->cache_key);
            }
            if($this->isRestApi())
                return $this->successRes(['id'=>$id, 'name'=>$user['name'], 'email'=>$user['email'], 'status' => $user['status'], 'created_at'=>$user['created_at'], 'updated-at'=>$user['updated_at'] ]);
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
        $current_user_status = $this->current_user->status;
        $id = $request['id'] * 1;
        $record = $this->removeData($current_user_status, $id);
        Cache::forget($this->cache_key);
        return json_encode($record);
    }
    /**
     * Disabled a user.
     *
     * @param Request $request
     * @return string
     */
    public function disable(Request $request) {
        $current_user_status = $this->current_user->status;
        $id = $request['id'] * 1;
        $record = $this->disableData($current_user_status, $id);
        return json_encode($record);
    }

    public function multiOperation(){
        $user_id = $this->current_user->id;
        $current_user_status = $this->current_user->status;
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
     * Restful Api Function - Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return User
     */
    public function store(Request $request)
    {
        $current_user_status = (int) $this->current_user->status;
        if ($current_user_status === 0) {
            if ( $this->isRestApi() ) {
                return $this->errorRes(trans('validation.user.disabled_power',['op' => 'create']));
            }
            $request->session()->flash('waring', trans('validation.user.disabled_power',['op' => 'create']));
            return Redirect::to('user');
        }
        if ($request['status'] == 4) {
            $status = 0;
        } else {
            $status = $request['status'];
        }
        $result_data = User::create(['name' => $request['name'], 'email' => $request['email'], 'password' => bcrypt($request['password']), 'status' => $status]);
        if ( $this->isRestApi() ) {
            return $this->successRes($result_data);
        }
        $request->session()->flash('success', trans('validation.user.create_success'));
        return Redirect::to('user');
    }
    /**
     * Restful Api Function - remove a user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id) {
        if(!$this->isRestApi()){
            return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
        }
        $current_user_status = (int) $this->current_user->status ? (int) $this->current_user->status : 0 ;
        $id = (int) $request['id'];
        $record = $this->removeData($current_user_status, $id);
        if(empty($record)){
            return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
        }
        Cache::flush();
        return $this->successRes(trans('validation.user.successful'));
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
        if(empty($user)){
            return [];
        }
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
