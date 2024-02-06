<?php

namespace App\Http\Requests;

use App\Exceptions\ApiRequestExcept;
use App\Exceptions\SystemErrorExcept;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiBaseRequest extends FormRequest
{

    protected $code = 10000;

    protected $error;

    /**
     * @param null $msg
     * @param $code
     * @throws SystemErrorExcept
     */
    public function error($msg, $code)
    {
        throw new SystemErrorExcept($msg, $code);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * @param Validator $validator
     * @throws ApiRequestExcept
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ApiRequestExcept(
            $validator->errors()->first(),
            $this->code
        );
    }
}
