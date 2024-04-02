<?php

use App\CategoryContestant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CategoryContestantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(CategoryContestant::class)->create();
    }
}
