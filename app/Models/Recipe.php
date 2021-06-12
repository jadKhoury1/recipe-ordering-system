<?php

namespace App\Models;

use App\Base\BaseModel;

class Recipe extends BaseModel
{

    /**
     * Specify the fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];


    /**
     * Get the ingredients associated with the recipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(
            Ingredient::class, 'recipe_ingredients', 'recipe_id', 'ingredient_id'
        )->withPivot('ingredient_amount');
    }

}
