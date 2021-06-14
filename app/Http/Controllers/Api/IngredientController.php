<?php

namespace App\Http\Controllers\Api;


use Carbon\Carbon;
use App\Models\Ingredient;
use App\Base\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use App\Http\Requests\AddIngredientRequest;
use App\Http\Requests\GetRequiredIngredients;

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
        return $this->response->statusOk(Ingredient::query()->orderByDesc('id')->simplePaginate());
    }

    public function getRequired(GetRequiredIngredients $request)
    {
        $orderDate = $request->validated()['order_date'];

        $ingredients = Ingredient::query()
            ->joinSub($this->getTotalIngredientsQuery($orderDate), 'total_ingredients', function (JoinClause $join) {
                $join->on('id', '=', 'ingredient_id');
            })
            ->get();

        return $this->response->statusOk(['ingredients' => $ingredients]);
    }

    /**
     * Build Query that fetches the total amount per ingredients for recipes
     * ordered between the supplied order date + 7 days
     *
     * @param $orderDate
     * @return Builder
     */
    private function getTotalIngredientsQuery($orderDate)
    {
        $totalRecipes = $this->buildTotalRecipesQuery($orderDate);

        return DB::table('recipe_ingredients')
            ->select(['ingredient_id', DB::raw('sum(ingredient_amount * total_recipes) as required_amount')])
            ->joinSub($totalRecipes, 'total_recipes', function (JoinClause $join) {
                $join->on('total_recipes.recipe_id', '=', 'recipe_ingredients.recipe_id');
            })
            ->groupBy('ingredient_id');
    }

    /**
     * Build Query that fetches the total of recipes for boxes ordered
     * between the supplied order date + 7 days
     *
     * @param $orderDate
     * @return Builder
     */
    private function buildTotalRecipesQuery($orderDate)
    {
       return DB::table('box_recipes')
            ->select(['recipe_id', DB::raw('count(*) as total_recipes')])
            ->whereIn('box_id', function (Builder $query) use ($orderDate) {
                $query->select('id')
                    ->from('boxes')
                    ->whereBetween(
                        'delivery_date', [$orderDate, Carbon::parse($orderDate)->addDays(7)]
                    );
            })
            ->groupBy('recipe_id');
    }

}