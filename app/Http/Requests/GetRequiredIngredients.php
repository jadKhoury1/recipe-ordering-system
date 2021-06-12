<?php

namespace App\Http\Requests;

use App\Base\BaseFormRequest;
use Carbon\Carbon;


class GetRequiredIngredients extends BaseFormRequest
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
            'order_date' => [
                'bail', 'required', 'date', 'date_format:Y-m-d H:i',
                'after_or_equal:' . Carbon::now()
            ]
        ];
    }
}
