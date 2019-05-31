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
            'username' => 'admin',
            'password' => Hash::make('password'),
            'name' => 'Administrator',
			'role' => 'admin',
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'Casual Attire',
                'description' => 'Casual Attire',
            ],
            [
                'name' => 'Play Suit',
                'description' => 'Play Suit',
            ],
            [
                'name' => 'Long Gown',
                'description' => 'Long Gown',
            ],
            [
                'name' => 'Interview',
                'description' => 'Interview',
            ],
        ]);

        DB::table('criterias')->insert([
            [
                'name' => 'Mastery and Execution',
                'description' => 'Mastery and Execution',
            ],
            [
                'name' => 'Gracefulness and Certainly during Performance',
                'description' => 'Gracefulness and Certainly during Performance',
            ],
            [
                'name' => 'Poise and Bearing',
                'description' => 'Poise and Bearing',
            ],
            [
                'name' => 'Audience Impact',
                'description' => 'Audience Impact',
            ],
            [
                'name' => 'Carriage',
                'description' => 'Carriage',
            ],
            [
                'name' => 'Beauty',
                'description' => 'Beauty',
            ],
            [
                'name' => 'Elegance and Sophistication',
                'description' => 'Elegance and Sophistication',
            ],
            [
                'name' => 'Articulation, Diction, Grammar',
                'description' => 'Articulation, Diction, Grammar',
            ],
            [
                'name' => 'Delivery and Choice of words',
                'description' => 'Delivery and Choice of words',
            ],
            [
                'name' => 'Relevance, Content, Wit and Impact',
                'description' => 'Relevance, Content, Wit and Impact',
            ],
            [
                'name' => 'Physical Attributes',
                'description' => 'Physical Attributes',
            ],
            [
                'name' => 'Talent',
                'description' => 'Talent',
            ],
            [
                'name' => 'Wit and Intelligence',
                'description' => 'Wit and Intelligence',
            ],
        ]);

        DB::table('criterias')->insert([
            [
                'name' => 'Mastery and Execution',
                'description' => 'Mastery and Execution',
            ],
            [
                'name' => 'Gracefulness and Certainly during Performance',
                'description' => 'Gracefulness and Certainly during Performance',
            ],
            [
                'name' => 'Poise and Bearing',
                'description' => 'Poise and Bearing',
            ],
            [
                'name' => 'Audience Impact',
                'description' => 'Audience Impact',
            ],
            [
                'name' => 'Carriage',
                'description' => 'Carriage',
            ],
            [
                'name' => 'Beauty',
                'description' => 'Beauty',
            ],
            [
                'name' => 'Elegance and Sophistication',
                'description' => 'Elegance and Sophistication',
            ],
            [
                'name' => 'Articulation, Diction, Grammar',
                'description' => 'Articulation, Diction, Grammar',
            ],
            [
                'name' => 'Delivery and Choice of words',
                'description' => 'Delivery and Choice of words',
            ],
            [
                'name' => 'Relevance, Content, Wit and Impact',
                'description' => 'Relevance, Content, Wit and Impact',
            ],
            [
                'name' => 'Physical Attributes',
                'description' => 'Physical Attributes',
            ],
            [
                'name' => 'Talent',
                'description' => 'Talent',
            ],
            [
                'name' => 'Wit and Intelligence',
                'description' => 'Wit and Intelligence',
            ],
        ]);
    }
}
