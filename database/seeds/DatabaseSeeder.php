<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'name' => 'Samuel Magana',
            'username' => 'maganasamuel001',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
