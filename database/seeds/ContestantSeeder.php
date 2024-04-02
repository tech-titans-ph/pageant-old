<?php

use App\Contestant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ContestantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(Contestant::class)->create();
    }
}
