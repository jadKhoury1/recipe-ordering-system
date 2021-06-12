<?php

namespace App\Models;

use App\Base\BaseModel;

class Box extends BaseModel
{
    /**
     * Set the fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['delivery_date'];


    /**
     * Get the recipes associated with the box
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipes()
    {
        return $this->belongsToMany(
            Recipe::class, 'box_recipes', 'box_id', 'recipe_id'
        );
    }
}
