<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addArticleRequest extends ApiBaseRequest
{
    protected $code = 10003;

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
            'title' => 'required|min:1',
            'meta_title' => 'required',
            'meta_keywords' => '',
            'meta_description' => '',
            'image' => 'required',
            'video_url' => ''
        ];
    }
}
