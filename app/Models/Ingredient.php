<?php

namespace App\Models;

use App\Base\BaseModel;

class Ingredient extends BaseModel
{
    /**
     * Set hidden field
     *
     * @var array
     */
    protected $hidden = ['measure'];

    /**
     * Eager load relations
     *
     * @var array
     */
    protected $with = ['measure'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['measurement_value'];

    /**
     * Set the fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name', 'supplier', 'measure_id'
    ];

    /**
     * Get the measurement value associated with the Ingredient
     *
     * @return string
     */
    public function getMeasurementValueAttribute()
    {
        return isset($this->measure) ? $this->measure->name : '';
    }


    /**
     * Get the measure record associated with the ingredient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function measure()
    {
        return $this->belongsTo(Measure::class, 'measure_id');
    }
}
