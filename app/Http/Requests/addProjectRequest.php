<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addProjectRequest extends ApiBaseRequest
{
    protected $code = 10010;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required'
        ];
    }
}
