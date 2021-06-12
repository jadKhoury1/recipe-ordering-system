<?php

namespace App\Http\Controllers\Api;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Base\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AddRecipeRequest;

class RecipeController extends BaseController
{
    public function add(AddRecipeRequest $request)
    {
        $data = $request->validated();
        $ingredients = collect($data['ingredients'])->mapWithKeys(function ($ingredient) {
            return [$ingredient['id'] => ['ingredient_amount' => $ingredient['amount']]];
        });

        DB::beginTransaction();
        try {
            $recipe = Recipe::query()->create($data);
            $recipe->ingredients()->attach($ingredients);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating recipe: ' .  $e->getMessage());
            return $this->response->statusFail(['message' => 'Recipe Could not be created']);
        }
        DB::commit();

        Ingredient::addAppendAttributes(['amount']);
        $recipe->load('ingredients');
        return $this->response->statusOk([
            'message' => 'Recipe Added Successfully',
            'recipe'  => $recipe
        ]);
    }

    public function get()
    {
        Ingredient::addAppendAttributes(['amount']);
        $recipes = Recipe::query()
            ->with('ingredients')
            ->orderByDesc('id')
            ->cursorPaginate();

        return $this->response->statusOk($recipes);
    }
}