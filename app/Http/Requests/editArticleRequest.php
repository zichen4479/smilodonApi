<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editArticleRequest extends ApiBaseRequest
{
    protected $code = 10004;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'site_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'meta_title' => 'required',
            'meta_keywords' => '',
            'meta_description' => '',
            'thumb' => '',
            'video_url' => ''
        ];
    }
}
