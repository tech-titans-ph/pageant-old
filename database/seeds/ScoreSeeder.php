<?php

use App\Score;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(Score::class)->create();
    }
}
