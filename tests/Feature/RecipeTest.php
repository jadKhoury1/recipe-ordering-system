<?php

namespace Tests\Feature;

use Database\Seeders\MeasureSeeder;
use Tests\TestCase;
use App\Base\BaseResponse;
use App\Models\Ingredient;
use Database\Seeders\RecipeSeeder;
use Database\Seeders\IngredientSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RecipeTest extends TestCase
{

    use RefreshDatabase, WithoutMiddleware;


    /**
     * Create a recipe and return the test response
     *
     * @return \Illuminate\Testing\TestResponse
     */
    private function createRecipe()
    {
        $this->seed([
            MeasureSeeder::class,
            IngredientSeeder::class
        ]);

        $ingredients = Ingredient::query()->inRandomOrder()->limit(5)
            ->get()->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id, 'amount' => rand(1, 10)
                ];
            })->toArray();


        return $this->postJson('/api/recipe', [
            'name'        => 'Test Recipe',
            'description' => 'Test description',
            'ingredients' => $ingredients
        ]);
    }


    /**
     * Test case hat verifies that a recipe record was created after hitting the endpoint
     *
     * @return void
     */
    public function test_create_recipe_success_database()
    {
        $this->createRecipe();

        $this->assertDatabaseHas('recipes', [
            'name' => 'Test Recipe', 'description' => 'Test description'
        ])->assertDatabaseCount('recipe_ingredients', 5);
    }

    /**
     * Test case hat verifies that the correct response is returned record after creating a recipe
     *
     * @return void
     */
    public function test_create_recipe_success_response()
    {
          $this->createRecipe()
              ->assertJson( function (AssertableJson $json) {
                $json->where('status', BaseResponse::OK)
                    ->has('response.recipe', function (AssertableJson $json) {
                        $json->where('name', 'Test Recipe')
                            ->where('description', 'Test description')
                            ->has('ingredients', 5)
                            ->whereType('id', 'integer')
                            ->etc();
                    });

            });
    }



    /**
     * Create Recipe with invalid ingredient array
     *
     * @return void
     */
    public function test_create_recipe_invalid_ingredient_array()
    {
        $response = $this->postJson('/api/recipe', [
            'name'        => 'Test Recipe',
            'description' => 'Test description',
            'ingredients' => ['id' => 1, 'amount' => 4]
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => BaseResponse::FAILED])
            ->assertJsonPath('response.message', 'Ingredient must be an array');
    }


    /**
     * Create Recipe with invalid ingredient ID
     *
     * @return void
     */
    public function test_create_recipe_invalid_ingredient_id()
    {
        $response = $this->postJson('/api/recipe', [
            'name'        => 'Test Recipe',
            'description' => 'Test description',
            'ingredients' => [['id' => 1, 'amount' => 4]]
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => BaseResponse::FAILED])
            ->assertJsonPath('response.message', 'ingredients IDs are not valid');
    }

    /**
     * Create recipe with invalid ingredient amount supplied
     *
     * @return void
     */
    public function test_create_recipe_invalid_amount_value()
    {
        $response = $this->postJson('/api/recipe', [
            'name'        => 'Test Recipe',
            'description' => 'Test description',
            'ingredients' => [['id' => 1, 'amount' => 0]]
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => BaseResponse::FAILED])
            ->assertJsonPath('response.message', 'The amount must be at least 1.');
    }

    /**
     * Try to create recipe by sending empty params
     *
     * @return void
     */
    public function test_create_recipe_empty_params()
    {
        $response = $this->postJson('/api/recipe', []);
        $response->assertStatus(403)
            ->assertJson(['status' => BaseResponse::FAILED]);
    }

    /**
     * Test the response of fetching all recipes
     *
     * @return void
     */
    public function test_get_recipes()
    {
        $this->seed([
            MeasureSeeder::class,
            IngredientSeeder::class,
            RecipeSeeder::class
        ]);

        $response = $this->getJson('/api/recipes');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('status', BaseResponse::OK)
                    ->whereType('response', 'array')
                    ->has('response', function (AssertableJson $json) {
                        $json->where('current_page', 1)
                            ->has('first_page_url')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has('path')
                            ->where('current_page', 1)
                            ->where('from', 1)
                            ->where('per_page', 20)
                            ->where('to', 20)
                            ->whereType('data', 'array')
                            ->has('data', 20)
                            ->has('data.0', function (AssertableJson $json) {
                                $json->has('id')
                                    ->whereType('id', 'integer')
                                    ->has('name')
                                    ->has('description')
                                    ->has('ingredients')
                                    ->whereType('ingredients', 'array')
                                    ->etc();

                            });
                    });
            });
    }

}
