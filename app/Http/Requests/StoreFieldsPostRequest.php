<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreFieldsPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key'   => 'required|min:2|max:64|unique:fields',
            'label' => 'required|min:2|max:64|unique:fields',
            'params' => 'required',
        ];
    }
}
