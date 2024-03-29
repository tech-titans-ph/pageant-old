<?php

use App\CategoryJudge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CategoryJudgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(CategoryJudge::class)->create();
    }
}
