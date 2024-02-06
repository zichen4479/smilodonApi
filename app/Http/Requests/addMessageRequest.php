<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addMessageRequest extends ApiBaseRequest
{

    protected $code = 10012;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email_address' => 'required|email',
            'comment' => 'required'
        ];
    }
}
