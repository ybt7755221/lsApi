<?php

namespace App\Http\Controllers;
use App\Http\Requests\Request;
use DB;
use Auth;
use Cache;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\Location;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    protected $pagination_number;
    protected $base_path = '/uploads';
    protected $rest_name = '/api';
    protected $current_user = false;
    protected $cache_key = '';
    protected $cache_time = 60 * 60 * 24;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pagination_number = 25;
        if(!$this->isRestApi()) {
            $this->middleware('auth');
            $this->current_user = json_decode(json_encode(Auth::user()['attributes']), FALSE);
        }else{
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $this->current_user = $this->getUserForApi($_GET['access_token']);
            }else{
                $this->current_user = $this->getUserForApi($_POST['access_token']);
            }
        }
        $this->cache_key = substr(md5($_SERVER['REQUEST_URI']), 8, 16);
    }
    /**
     * Judge a request weather is restful api.
     *
     * @return bool
     */
    protected function isRestApi(){
        if( stripos($_SERVER['REQUEST_URI'], $this->rest_name) > 0 ) {
            return true;
        }
        return false;
    }

    /**
     * Return success json data
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successRes($data) {
        return $this->ResponseJson(1, $data);
    }
    /**
     * Return error json data
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorRes($data) {
        return $this->ResponseJson(0, $data);
    }
    /**
     * Return a json data. This function is strand for successRes and errorRes.
     *
     * @param $success
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function ResponseJson($success, $data){
        return response()->json(['success'=>$success,'result'=>$data]);
    }
    /**
     * check user allow.
     *
     * @param $level
     * @param $op
     * @return bool
     */
    public function userDisable($level, $op) {
        $res = false;
        if(!empty($level) && !empty($op)) {
            $allow_list=[
                    [],
                    ['create','edit','update','delete','refresh'],
                    ['create','edit','update','delete','refresh'],
                    ['create','edit','update','delete','refresh'],
            ];
            if($level == 4){
                $res = true;
            }else if($level > 0 && $level < 4){
                if(in_array($op, $allow_list[$level])){
                    $res = true;
                }
            }
        }
        return $res;
    }
    /**
     *
     */
    /**
     * Upload a image for content.
     *
     * @return array
     */
    protected function uploadImage($base_path) {
        $record = ['success' => 1];
        $microtime = microtime(true);
        $target_dir = $base_path.date('/Y-m/', $microtime);
        $filename = explode('.', basename($_FILES["thumb"]["name"]));
        $target_name = 'LS'.str_replace('.', '_', $microtime).'.'.end($filename);
        $imageFileType = substr($_FILES['thumb']['type'], stripos($_FILES['thumb']['type'], '/')+1);
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            if ($record['success'] === 0)
                $record['result'] += '<br />'.trans('validation.format_error');
            else
                $record = ['success' => 0, 'result' => trans('validation.format_error')];
        }
        if($_FILES['thumb']['size'] > 5*1000*1000) {
            if ($record['success'] === 0)
                $record['result'] += '<br />'.trans('validation.so_big');
            else
                $record = ['success' => 0, 'result' => trans('validation.so_big')];
        }
        if ($record['success'] === 1) {
            $mk_res = true;
            if(!is_dir(storage_path().$target_dir)) {
                $mk_res = @mkdir(storage_path().$target_dir, 0755, true);
            }
            if ($mk_res) {
                if (move_uploaded_file($_FILES["thumb"]["tmp_name"], storage_path().$target_dir . $target_name)) {
                    $record['result'] = '/storage' . $target_dir . $target_name;
                } else {
                    $record = ['success' => 0, 'result' => trans('errors.LS40401_UNKNOWN')];
                }
            }else{
                $record = ['success' => 0, 'result' => trans('errors.LS40301_INFO')];
            }
        }
        return $record;
    }

    /**
     * Return a error information.
     *
     * @return $this
     */
    protected function error()
    {
        return view('errors/503')->with('errorInfo', ['errorCode' => trans('errors.LS40301_NAME'), 'errorMsg' => trans('errors.LS40301_INFO')]);
    }

    /**
     * Get User Information for Api request.
     *
     * @param $access_token
     * @return bool
     */
    protected function getUserForApi($access_token){
        $result = false;
        if(empty($access_token) ){
            return $result;
        }
        $oauth_access_token = DB::table('oauth_access_tokens')->where('id',$access_token)->first();
        if(isset($oauth_access_token->session_id) && !empty($oauth_access_token->session_id) ){
            $oauth_sessions = DB::table('oauth_sessions')->where('id',$oauth_access_token->session_id)->first();
            if(isset($oauth_sessions->owner_id) && !empty($oauth_sessions->owner_id)) {
               $result = DB::table('users')->where('id',$oauth_sessions->owner_id)->first();
            }
        }
        return $result;
    }

    /**
     * Check if have cache and return the cache data.
     *
     * @return bool
     */
    protected function checkCache() {
        if( Cache::has($this->cache_key) ){
            return Cache::get($this->cache_key);
        }
        return false;
    }
}
