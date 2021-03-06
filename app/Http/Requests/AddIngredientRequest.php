<?php

namespace App\Http\Requests;

use App\Base\BaseFormRequest;

class AddIngredientRequest extends BaseFormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'bail|required|string|between:3,255',
            'supplier'   => 'bail|required|string|between:3,35',
            'measure_id' => 'bail|required|integer|exists:measures,id'
        ];
    }


}
