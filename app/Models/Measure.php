<?php

namespace App\Models;

use App\Base\BaseModel;

class Measure extends BaseModel
{

    /**
     * Specify the fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['name'];

}
