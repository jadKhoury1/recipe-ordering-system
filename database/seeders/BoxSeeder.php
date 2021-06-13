<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allRecipes = Recipe::all();
        Box::factory()
            ->count(100)
            ->create()
            ->each(function ($box) use ($allRecipes){
                $recipes = $allRecipes->random(rand(1,4))->pluck('id')->toArray();
                $box->recipes()->attach($recipes);
            });
    }
}
