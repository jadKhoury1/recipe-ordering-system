<?php

namespace Database\Seeders;

use App\Models\Measure;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Measure::query()->insertOrIgnore([
            [
                'name' => 'kg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'name' => 'g', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'name' => 'pieces', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]
        ]);
    }
}
