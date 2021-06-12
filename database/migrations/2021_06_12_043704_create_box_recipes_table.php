<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box_recipes', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id');
            $table->unsignedBigInteger('recipe_id');

            $table->foreign('box_id')
                  ->references('id')
                  ->on('boxes')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');

            $table->foreign('recipe_id')
                  ->references('id')
                  ->on('recipes')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('box_recipes');
    }
}
