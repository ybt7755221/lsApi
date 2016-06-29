<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Link;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreLinkPostRequest;
use Auth;

class LinkController extends Controller
{
    public function removed(Request $request) {
        if ( $this->userDisable(Auth::user()->status, 'remove') ) {
            $result = Link::destroy((int)$request['id']);
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
