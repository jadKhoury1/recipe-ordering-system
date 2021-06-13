<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Measure;
use App\Base\BaseResponse;
use Database\Seeders\MeasureSeeder;
use Database\Seeders\IngredientSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Run a Measure seeder before each test.
     *
     * @var string
     */
    protected $seeder = MeasureSeeder::class;

    /**
     * Create Ingredient test
     *
     * @return void
     */
    public function test_create_ingredient_success()
    {
        $measure = Measure::query()->first();

        $response = $this->postJson('/api/ingredient', [
            'name'       => 'Tomato',
            'supplier'   => 'Test Supplier',
            'measure_id' => $measure->id
        ]);

        $response->assertStatus(200)
                 ->assertJson( function (AssertableJson $json) use ($measure) {
                     $json->where('status', BaseResponse::OK)
                          ->has('response.ingredient', function (AssertableJson $json) use ($measure) {
                                $json->where('name', 'Tomato')
                                     ->where('supplier', 'Test Supplier')
                                     ->where('measure_id', $measure->id)
                                     ->whereType('id', 'integer')
                                     ->etc();
                          });

                 });
    }

    /**
     * Create Ingredient with invalid measure ID
     *
     * @return void
     */
    public function test_create_ingredient_invalid_measure_id()
    {
        $response = $this->postJson('/api/ingredient', [
            'name'       => 'test',
            'supplier'   => 'test',
            'measure_id' => 100
        ]);

        $response->assertStatus(403)
                 ->assertJson(['status' => BaseResponse::FAILED])
                 ->assertJsonPath('response.message', 'The selected measure id is invalid.');
    }

    /**
     * Try to create Ingredient by sending empty params
     *
     * @return void
     */
    public function test_create_ingredient_empty_params()
    {
        $response = $this->postJson('/api/ingredient', []);
        $response->assertStatus(403)
                 ->assertJson(['status' => BaseResponse::FAILED]);
    }

    /**
     * Test the response of fetching all ingredients
     *
     * @return void
     */
    public function test_get_ingredients()
    {
        $this->seed(IngredientSeeder::class);

        $response = $this->getJson('/api/ingredients');


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
                                               ->has('supplier')
                                               ->has('measurement_value')
                                               ->etc();

                                      });
                             });
                 });
    }

}
