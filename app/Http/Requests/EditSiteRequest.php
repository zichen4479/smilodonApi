<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditSiteRequest extends ApiBaseRequest
{
    protected $code = 10006;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'system_setting_language_id' => 'required',
            'site_name' => 'required',
            'meta_title' => 'required'
        ];
    }
}
