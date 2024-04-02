<?php

use App\Criteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(Criteria::class)->create();
    }
}
