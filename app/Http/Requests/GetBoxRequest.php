<?php

namespace App\Http\Requests;

use App\Base\BaseFormRequest;

class GetBoxRequest extends BaseFormRequest
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
            'from_delivery_date' => 'sometimes|date_format:Y-m-d H:i',
            'to_delivery_date'   => 'sometimes|date_format:Y-m-d H:i|after:from_date'
        ];
    }
}
