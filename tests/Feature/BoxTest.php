<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Recipe;
use App\Base\BaseResponse;
use Database\Seeders\RecipeSeeder;
use Database\Seeders\MeasureSeeder;
use Database\Seeders\IngredientSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BoxTest extends TestCase
{

    use RefreshDatabase, WithoutMiddleware;


    /**
     * Create a box and return the test response
     *
     * @return \Illuminate\Testing\TestResponse
     */
    private function createBox(&$deliveryDate)
    {
        $this->seed([
            MeasureSeeder::class,
            IngredientSeeder::class,
            RecipeSeeder::class
        ]);

        // get four random recipe IDs from database
        $recipeIds = Recipe::query()
            ->select('id')->inRandomOrder()
            ->limit(4)->pluck('id')
            ->toArray();

        // Set delivery date four days from now
        $deliveryDate = Carbon::now()->addDays(4)->format('Y-m-d H:i');

        return $this->postJson('/api/box', [
            'delivery_date' => $deliveryDate,
            'recipe_ids'    => $recipeIds
        ]);


    }

    /**
     * Test case hat verifies that a box record was created after hitting the endpoint
     *
     * @return void
     */
    public function test_create_box_success_database()
    {
        $this->createBox($deliveryDate);

        $this->assertDatabaseHas('boxes', [
             'delivery_date' => $deliveryDate
        ])->assertDatabaseCount('box_recipes', 4);
    }

    /**
     * Test case hat verifies that the correct response is returned record after creating a box
     *
     * @return void
     */
    public function test_create_box_success_response()
    {
        $this->createBox($deliveryDate)
            ->assertJson( function (AssertableJson $json) use ($deliveryDate) {
                $json->where('status', BaseResponse::OK)
                    ->has('response.box', function (AssertableJson $json) use ($deliveryDate) {
                        $json->where('delivery_date', $deliveryDate)
                            ->whereType('id', 'integer')
                            ->has('recipes', 4)
                            ->whereType('recipes', 'array')
                            ->has('recipes.0', function (AssertableJson $json) {
                                $json->has('ingredients')
                                     ->whereType('ingredients', 'array')
                                     ->etc();
                            });
                    });

            });
    }

    /**
     * Create a Box with invalid delivery date
     *
     * @return void
     */
    public function test_create_box_invalid_delivery_date()
    {
        $response = $this->postJson('/api/box', [
            'delivery_date' => Carbon::now()->format('Y-m-d H:i'),
            'recipe_ids'    => [1,2]
        ]);


        $response->assertStatus(403)
            ->assertJson(['status' => BaseResponse::FAILED]);
    }

    /**
     * Create a box with invalid recipe IDs
     *
     * @return void
     */
    public function test_create_box_invalid_recipe_ids()
    {
        $response = $this->postJson('/api/box', [
            'delivery_date' => Carbon::now()->addDays(4)->format('Y-m-d H:i'),
            'recipe_ids'    => [1,2]
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => BaseResponse::FAILED])
            ->assertJsonPath('response.message', 'recipe_ids are not valid');
    }
}
