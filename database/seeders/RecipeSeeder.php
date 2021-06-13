<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         $allIngredients = Ingredient::all();
         Recipe::factory()
             ->count(50)
             ->create()
             ->each(function ($recipe) use ($allIngredients) {
                    $ingredients = $allIngredients->random(rand(3,7))->mapWithKeys(function ($ingredient) {
                        return [$ingredient['id'] => ['ingredient_amount' => rand(2,5)]];
                    });
                    $recipe->ingredients()->attach($ingredients);
             });
    }
}
