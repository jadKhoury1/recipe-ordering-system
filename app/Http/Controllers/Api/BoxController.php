<?php

namespace App\Http\Controllers\Api;

use App\Models\Box;
use App\Models\Ingredient;
use App\Base\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AddBoxRequest;

class BoxController extends BaseController
{
    public function add(AddBoxRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $box = Box::query()->create($data);
            $box->recipes()->attach($data['recipe_ids']);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Creating Box: ' . $e->getMessage());
            return $this->response->statusFail(['message' => 'Box could not be created']);
        }
        DB::commit();

        Ingredient::addAppendAttributes(['amount']);
        $box->load('recipes.ingredients');

        return $this->response->statusOk([
            'message' => 'Box created successfully',
            'box'     => $box
        ]);
    }
}