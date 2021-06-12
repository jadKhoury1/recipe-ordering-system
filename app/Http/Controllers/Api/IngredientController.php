<?php

namespace App\Http\Controllers\Api;


use App\Base\BaseController;
use App\Http\Requests\AddIngredientRequest;
use App\Http\Requests\GetRequiredIngredients;
use App\Models\Ingredient;

class IngredientController extends BaseController
{

    public function add(AddIngredientRequest $request)
    {
        $ingredient = Ingredient::query()->create($request->validated());

        return $this->response->statusOk([
            'message'    => 'Ingredient added successfully',
            'ingredient' => $ingredient
        ]);
    }

    public function get ()
    {
        return $this->response->statusOk(Ingredient::query()->orderByDesc('id')->cursorPaginate());
    }

    public function getRequired(GetRequiredIngredients $request)
    {
        
    }
}