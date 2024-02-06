<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPageRequest extends ApiBaseRequest
{

    protected $code = 10007;

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
            'meta_title' => 'required',
        ];
    }
}
