<?php

namespace App\Base;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest {

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;


    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(BaseResponse::forbidden(['message' => $validator->errors()->first()]));
    }


    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(BaseResponse::unauthorized(['message' => 'Unauthorized Access']));
    }

}