<?php

namespace App\Http\Controllers;

use Symfony\Component\VarDumper\Cloner\Data;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class OauthController extends Controller
{
    private $table = 'oauth_clients';
    public function index() {
        $oauth_id = Auth::user()->oauth_id != 'NULL' ? Auth::user()->oauth_id : 'NULL' ;
        $oauth_client = DB::table('oauth_clients')->where('id', $oauth_id)->first();
        return view('oauth/index',['oauth_obj' => $oauth_client]);
    }
    public function create(Request $request){
        $user = User::find((int) $request['id']);
        $a = $user->email;
        if($this->userDisable($user->status, 'create')){
            if($user->oauth_id == 'NULL' || empty($user->oauth_id) ){
                $oauth_id = substr(md5($user->email),8,16);
                $oauth = DB::table('oauth_clients')->insert([
                        'id' => $oauth_id,
                        'secret' => md5($user->email.$user->id.$user->created_at),
                        'name' => $user->name."'s oauth secret",
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
                if($oauth){
                    $user->oauth_id = $oauth_id;
                    $user->save();
                    return $this->successRes(trans('validation.user.successful'));
                }
            }
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'edit']));
        }
        return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
    }

    public function store(Request $request) {
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'create')){
            $user = User::find(Auth::user()->id);
            if($user->oauth_id == 'NULL' || empty($user->oauth_id) ){
                $oauth_id = substr(md5(Auth::user()->email),8,16);
                $oauth = DB::table('oauth_clients')->insert([
                        'id' => $oauth_id,
                        'secret' => md5(Auth::user()->email.Auth::user()->id.Auth::user()->created_at),
                        'name' => Auth::user()->name."'s oauth secret",
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
                if($oauth){
                    $user->oauth_id = $oauth_id;
                    $user->save();
                    return $this->successRes(trans('validation.user.successful'));
                }
            }
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'create']));
        }
        return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
    }

    public function update(Request $request){
        $current_user_status = Auth::user()->status;
        if ($this->userDisable($current_user_status, 'refresh')){
            if ( $this->removeData(Auth::user()->oauth_id) ){
                $random = random_int(11, 99);
                $oauth_id = substr(md5(Auth::user()->email.$random),8,16);
                $oauth_secret = md5(Auth::user()->email.Auth::user()->id.Auth::user()->created_at.$random);
                $oauth = DB::table('oauth_clients')->insert([
                        'id' => $oauth_id,
                        'secret' => $oauth_secret,
                        'name' => Auth::user()->name."'s oauth secret",
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
                if($oauth){
                    User::where('id',Auth::user()->id)->update(['oauth_id'=>$oauth_id]);
                    return $this->successRes(['info'=>trans('validation.user.successful'),'data'=>['id'=>$oauth_id, 'secret' => $oauth_secret]]);
                }
            }
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'refresh']));
        }
        return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
    }

    private function removeData($id){
        return DB::table($this->table)->where('id',$id)->delete();
    }
}
