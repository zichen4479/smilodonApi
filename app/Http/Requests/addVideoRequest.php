<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addVideoRequest extends ApiBaseRequest
{

    protected $code = 10023;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|min:10|max:2048000'
        ];
    }
}
