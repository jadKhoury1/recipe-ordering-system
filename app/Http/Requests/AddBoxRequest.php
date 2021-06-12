<?php

namespace App\Http\Requests;

use App\Models\Recipe;
use Carbon\Carbon;
use App\Rules\NumericArray;
use App\Base\BaseFormRequest;

class AddBoxRequest extends BaseFormRequest
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
            'delivery_date' => [
                'bail', 'required', 'date_format:Y-m-d H:i',
                'after:' . Carbon::now()->addHours(48)->startOfMinute()
            ],
            'recipe_ids' => [
                'bail', 'required', 'array', 'between:1,4', new NumericArray,
                function ($attribute, $values, $fail) {

                    // get only unique recipe IDs
                    $uniqueValues = array_unique($values);

                    // check if all recipe IDs are valid
                    $recipesCount = Recipe::query()->whereIn('id', $uniqueValues)->count();
                    // if the number of recipes returned from the database is different
                    // from the supplied number of IDs - then the IDs are not correct
                    if ($recipesCount !== count($uniqueValues)) {
                        return $fail("{$attribute} IDs are not valid");
                    }
                }
            ]

        ];
    }
}
