<?php

namespace Database\Factories;

use App\Models\Recipe;
use \FakerRestaurant\Provider\en_US\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Restaurant ($faker));
        return [
            'name'        => $faker->foodName(),
            'description' => $faker->text()
        ];
    }
}
