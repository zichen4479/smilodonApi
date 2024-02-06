<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditPageRequest extends ApiBaseRequest
{

    protected $code = 10008;
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
