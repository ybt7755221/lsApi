<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

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
    public function userDisable($level, $op) {
        $res = false;
        if ($level == 0){
            return $res;
        }
        switch ($op) {
            case 'create' :
                if ($level > 1) {
                    $res = true;
                }
                break;
            case 'edit' :
                if ($level > 0) {
                    $res = true;
                }
                break;
            case 'remove' :
                if ($level > 1) {
                    $res = true;
                }
                break;
            case 'display':
                if ($level > 0) {
                    $res = true;
                }
                break;
            default :
                break;
        }
        return $res;
    }
}
