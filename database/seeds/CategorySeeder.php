<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('migrate:fresh --seed');

        factory(Category::class)->create();
    }
}
