<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSiteRequest extends ApiBaseRequest
{
    protected $code = 10005;

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
            'meta_title' => 'required|string|min:3|max:60',
            'meta_keywords' => 'nullable|string|min:0|max:155',
            'meta_description' => 'nullable|string|min:0|max:160',
            'logo' => 'nullable|int',
            'mail_port' => 'nullable|int',
            'mail_username' => 'nullable|email',
            'receive_mail_address' => 'nullable|email'
        ];
    }

    public function messages()
    {
        return [
            'system_setting_language_id.required' => trans('site.required_system_setting_language_id'),
            'site_name.required' => trans('site.required_site_name'),
            'meta_tile.required' => trans('site.required_meta_title'),
            'mail_username.email' => trans('site.email_mail_username'),
            'receive_mail_address.email' => trans('site.email_mail_username'),
        ];
    }
}
