<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use \FakerRestaurant\Provider\en_US\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $measureIds = DB::table('measures')->pluck('id')->toArray();
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Restaurant ($faker));
        return [
            'name'       => $faker->vegetableName(),
            'supplier'   => $faker->name(),
            'measure_id' => $faker->randomElement($measureIds)
        ];
    }
}
