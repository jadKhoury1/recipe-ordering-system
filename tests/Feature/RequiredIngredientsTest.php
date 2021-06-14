<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Box;
use Faker\Generator;
use App\Models\Recipe;
use App\Base\BaseResponse;
use App\Models\Ingredient;
use Illuminate\Support\Arr;
use Database\Seeders\MeasureSeeder;
use Database\Seeders\IngredientSeeder;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use FakerRestaurant\Provider\en_US\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RequiredIngredientsTest extends TestCase
{

    use RefreshDatabase, WithoutMiddleware;

    /**
     * Holds an instance of the Faker Generator class
     *
     * @var Generator
     */
    private $faker;

    /**
     * Stores the ingredient IDs that will be used to validate the response
     *
     * @var array
     */
    private $ingredientIds;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = $faker = \Faker\Factory::create();
        $faker->addProvider(new Restaurant ($faker));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_required_ingredients()
    {
        $this->seed([
            MeasureSeeder::class,
            IngredientSeeder::class
        ]);

        // create a predefined set of boxes
        $this->createBoxes();

        // the order date will be one hour from now
        $orderDate = urlencode(Carbon::now()->addHour()->format('Y-m-d H:i'));

        $response = $this->getJson("/api/requiredIngredients?order_date={$orderDate}");
        // The assertions below will only make sure that the status is valid and that the ingredients array was returned
        $response->assertStatus(200)
                 ->assertJson( function (AssertableJson $json) {
                     $json->where('status', BaseResponse::OK)
                          ->has('response.ingredients')
                          ->whereType('response.ingredients', 'array');
                 });


        // Get all the ingredients from the returned response and set the ID as key for each ingredient
        $content = Collect(Arr::get(json_decode($response->getContent(), true), 'response.ingredients', []))
            ->keyBy('id')
            ->toJson();

        // The following assertions will make sure that the required amount
        // for each ingredient was returned correctly
        $ingredients = new AssertableJsonString($content);
        $ingredients->assertCount(3)
                    ->assertPath("{$this->ingredientIds[0]}.required_amount", 4)
                    ->assertPath("{$this->ingredientIds[1]}.required_amount", 2)
                    ->assertPath("{$this->ingredientIds[2]}.required_amount", 1);

    }

    /**
     * The Test Case will try to fetch the required ingredients with an invalid order date
     *
     * @return void
     */
    public function test_get_required_ingredients_invalid_order_date()
    {
        // the order date will be set to yesterday
        $orderDate = urlencode(Carbon::yesterday()->format('Y-m-d H:i'));

        $response = $this->getJson("/api/requiredIngredients?order_date={$orderDate}");
        $response->assertStatus(403)
                ->assertJson( function (AssertableJson $json) {
                    $json->where('status', BaseResponse::FAILED)
                          ->etc();
                });
    }


    /**
     * Create predefined boxes with different delivery dates and recipes
     *
     * @return void
     */
    private function createBoxes()
    {
        $recipeIds = $this->createRecipes();

        // create a box from the first recipe created
        $this->createOneBox(
            Carbon::now()->addDay()->format('Y-m-d H:i'), $recipeIds[0]
        );

        // create a box from the second recipe created
        $this->createOneBox(
            Carbon::now()->addDays(9)->format('Y-m-d H:i'), $recipeIds[1]
        );

        // create a box from the third recipe created
        $this->createOneBox(
            Carbon::now()->addDays(7)->format('Y-m-d H:i'), $recipeIds[2]
        );
    }


    /**
     * Create a recipe with a predefined delivery date and a set of recipe IDs
     *
     * @param $deliveryDate
     * @param $recipeIds
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function createOneBox($deliveryDate, $recipeIds)
    {
        $box = Box::query()->create(['delivery_date' => $deliveryDate]);
        $box->recipes()->attach($recipeIds);
        return $box;
    }

    /**
     * Create hardcoded recipes with a predefined set of ingredients
     *
     * @param $ingredients
     *
     * @return array
     */
    private function createRecipes()
    {
        // fetching only two ingredients from the database
        $ingredients = Ingredient::query()->inRandomOrder()->limit(3);

        $this->ingredientIds = $ingredients->pluck('id')->toArray();

        $recipeIds = [];

        $recipeIds[] = $this->createOneRecipe([
            $this->ingredientIds[0] => ['ingredient_amount' => 3],
            $this->ingredientIds[1] => ['ingredient_amount' => 1]
        ])->id;

        $recipeIds[] = $this->createOneRecipe([
            $this->ingredientIds[0] => ['ingredient_amount' => 2],
            $this->ingredientIds[2] => ['ingredient_amount' => 1]
        ])->id;

        $recipeIds[] = $this->createOneRecipe([
            $this->ingredientIds[0] => ['ingredient_amount' => 1],
            $this->ingredientIds[1] => ['ingredient_amount' => 1],
            $this->ingredientIds[2] => ['ingredient_amount' => 1]
        ])->id;

        return $recipeIds;

    }

    /**
     * Create a random recipe and attach it to the supplied ingredients
     *
     * @param $ingredients
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function createOneRecipe($ingredients) {
        $recipe = Recipe::query()->create([
            'name' => $this->faker->foodName(), 'description' => $this->faker->text()
        ]);

        // Set the amount of ingredients used for the recipe
        $recipe->ingredients()->attach($ingredients);

        return $recipe;
    }

}
