<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addFileRequest extends ApiBaseRequest
{
    protected $code = 10721;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files' => 'required|array',
            'files.*' => 'required|min:10|max:2048000'
        ];
    }
}
