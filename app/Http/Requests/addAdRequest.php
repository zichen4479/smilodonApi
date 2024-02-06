<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addAdRequest extends ApiBaseRequest
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
            'site_id' => 'required',
            'title' => 'required',
            'image' => 'required'
        ];
    }
}
