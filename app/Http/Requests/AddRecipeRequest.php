<?php

namespace App\Http\Requests;

use App\Base\BaseFormRequest;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Validator;


class AddRecipeRequest extends BaseFormRequest {

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
            'name'        => 'bail|required|string|between:3,255',
            'description' => 'bail|required|string',
            'ingredients' => [
                'bail', 'required', 'array',
                function ($attribute, $values, $fail) {

                    foreach ($values as $value) {
                        $validator = Validator::make($value, [
                            'id' => 'bail|required|integer',
                            'amount' => 'bail|required|numeric|min:1'
                        ]);

                        if ($validator->fails()) {
                            return $fail($validator->errors()->first());
                        }
                    }

                   // make sure there is no duplicate ingredients
                   $ids = collect($values)->pluck('id')->toArray();
                   $idsCount = count($ids);
                   if ($idsCount !== count(array_unique($ids))) {
                       return $fail("{$attribute} cannot be added more than once");
                   }

                   // check if all ingredient IDs are valid
                   $ingredientsCount = Ingredient::query()->whereIn('id', $ids)->count();
                   // if the number of ingredients returned from the database is different
                   // from the supplied number of IDs - then the IDs are not correct
                    if ($ingredientsCount !== $idsCount) {
                        return $fail("{$attribute} IDs are not valid");
                    }

                }
            ]

        ];
    }
}