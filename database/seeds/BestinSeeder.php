<?php

use App\Bestin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class BestinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(Bestin::class)->create();
    }
}
