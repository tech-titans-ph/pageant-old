<?php

use App\Judge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class JudgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(Judge::class)->create();
    }
}
