<?php

namespace App\Models;

use Carbon\Carbon;
use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Box extends BaseModel
{

    use HasFactory;

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

    /**
     * Scope that filters boxes within a specific date range
     *
     * @param Builder $query
     * @param string $fromDate
     * @param string $toDate
     */
    public function scopeFilterDeliveryDate(Builder $query, $fromDate, $toDate)
    {
        if ($fromDate !== null) {
            $query->where('delivery_date', '>=', Carbon::parse($fromDate)->startOfMinute());
        }

        if ($toDate) {
            $query->where('delivery_date', '<=', Carbon::make($toDate)->endOfMinute());
        }
    }
}
