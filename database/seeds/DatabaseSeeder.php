<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        collect(Storage::directories())->each(function ($directory) {
            Storage::deleteDirectory($directory);
        });

        /* DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Role::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1'); */

        $admin = factory(User::class)->create([
            'name' => 'Administrator',
            'username' => 'admin',
        ]);
    }
}
