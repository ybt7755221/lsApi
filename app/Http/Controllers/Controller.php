<?php

namespace App\Http\Controllers;
use DB;
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
        }
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
    protected function successRes($data) {
        return $this->ResponseJson(1, $data);
    }
    protected function errorRes($data) {
        return $this->ResponseJson(0, $data);
    }
    private function ResponseJson($success, $data){
        return response()->json(['success'=>$success,'result'=>$data]);
    }
    /**
     * check user allow.
     *
     * @param $level
     * @param $op
     * @param bool $auto_return
     * @return bool
     */
    public function userDisable($level, $op) {
        $res = false;
        if(!empty($level) && !empty($op)) {
            $allow_list=[
                    [],
                    ['create','edit','delete'],
                    ['create','edit','delete'],
                    ['create','edit','delete'],
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
}
