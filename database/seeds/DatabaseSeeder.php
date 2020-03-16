<?php

use App\User;
use Facades\Silber\Bouncer\Bouncer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Silber\Bouncer\Database\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory('logos');
        Storage::deleteDirectory('profile-pictures');

        /* DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Role::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1'); */

        foreach (config('services.roles') as $role) {
            Bouncer::role()->create($role);
        }

        $admin = factory(User::class)->states('admin')->create([
            'name' => 'Administrator',
            'username' => 'admin',
        ]);
    }
}
