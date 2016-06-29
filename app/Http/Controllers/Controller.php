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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pagination_number = 25;
        $this->middleware('auth');
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
}
