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
    public function index() {
        $oauth_id = Auth::user()->oauth_id != 'NULL' ? Auth::user()->oauth_id : 'NULL' ;
        $oauth_client = DB::table('oauth_clients')->where('id', $oauth_id)->first();
        return view('oauth/index',['oauth_obj' => $oauth_client]);
    }

    public function create(Request $request) {
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
            return $this->errorRes(trans('errors.LS40401_UNKNOWN'));
        }else{
            return $this->errorRes(trans('validation.user.disabled_power',['op' => 'eidt']));
        }
        return Redirect::to('/dashboard');
    }
}
