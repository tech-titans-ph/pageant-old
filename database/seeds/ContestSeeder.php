<?php

use App\Category;
use App\CategoryContestant;
use App\CategoryCriteria;
use App\CategoryJudge;
use App\Contest;
use App\Contestant;
use App\ContestCategory;
use App\Criteria;
use App\Judge;
use App\Score;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        /* $file = Storage::putFile('profile_pictures', new File(public_path('images/avatar-contestant.png')));

        dd($file); */

        Storage::deleteDirectory('logos');
        Storage::deleteDirectory('profile_pictures');

        User::truncate();
        Contest::truncate();
        Contestant::truncate();
        Judge::truncate();
        Category::truncate();
        ContestCategory::truncate();
        CategoryCriteria::truncate();
        Criteria::truncate();
        CategoryContestant::truncate();
        CategoryJudge::truncate();
        Score::truncate();

        factory(User::class)->create([
            'name' => 'Administrator',
            'username' => 'admin',
        ]);

        $contest = factory(Contest::class)->create();

        $contestants = [
            /* [
                'name' => '',
                'description' => '',
            ], */
            [
                'name' => 'Zylah Mae. C. De Leon',
                'description' => 'San Lorenzo Ruiz',
            ],
            [
                'name' => 'Dalia Clarissa G. Tabornal',
                'description' => 'Mancruz',
            ],
            [
                'name' => 'Rana V. Yousefpour',
                'description' => 'Labo',
            ],
            [
                'name' => 'Eliza Jane P. Omana',
                'description' => 'San Vicente',
            ],
            [
                'name' => 'Ella May C. Torreda',
                'description' => 'San Lorenzo',
            ],
            [
                'name' => 'Iris I. Oresca',
                'description' => 'Labo',
            ],
            [
                'name' => 'Sofia Isabel I. Cabral',
                'description' => 'Paracale',
            ],
            [
                'name' => 'Ara Vanessa Bellen',
                'description' => 'Capalonga',
            ],
            [
                'name' => 'Carla R. Abarca',
                'description' => 'Talisay',
            ],
            [
                'name' => 'Loviella Arissa Nicole Y. Cereno',
                'description' => 'San Lorenzo',
            ],
            [
                'name' => 'Valierie Torcelino',
                'description' => 'Mercedes',
            ],
            [
                'name' => 'Donna Sayson',
                'description' => 'Mercedes',
            ],
            [
                'name' => 'Grace Abegail Flores',
                'description' => 'Jose Panganiban',
            ],
            [
                'name' => 'Marjolisa B. Santiago',
                'description' => 'Daet',
            ],
            [
                'name' => 'Chicco Crisostomo',
                'description' => 'Labo',
            ],
            [
                'name' => 'Rhianne Macy N. Zapata',
                'description' => 'Daet',
            ],
            [
                'name' => 'Hescia Mae B. Casiano',
                'description' => 'Mercedes',
            ],
            [
                'name' => 'Jessaniel C. Buena',
                'description' => 'Basud',
            ],
            [
                'name' => 'Trixia Jorgia P. Agunan',
                'description' => 'Talisay',
            ],
            [
                'name' => 'Ronna Mae Mercado',
                'description' => 'San Vicente',
            ],
        ];

        $judges = [
            'Jan Marc Dilanco',
            'Febelin Peña',
            'Cherry D. Mitra',
            // 'Mariano "Bong" Palma',
            'Alex Alangco',
            'John Michael Aldea',
        ];

        $categories = [
            /* [
                'name' => '',
                'percentage' => 100,
                'criterias' => [
                    ['name' => '', 'percentage' => 25],
                ],
            ], */
            [
                'name' => 'Screening',
                'percentage' => 100,
                'criterias' => [
                    ['name' => 'Beauty of Face', 'percentage' => 25],
                    ['name' => 'Beauty of Figure/Body Proportion', 'percentage' => 25],
                    ['name' => 'Poise and Personality', 'percentage' => 25],
                    ['name' => 'Communication Skills/ Intelligence', 'percentage' => 25],
                ],
            ],
            /* [
                'name' => 'Communication Skills/ Intelligence',
                'percentage' => 25,
                'criterias' => [
                    ['name' => 'Communication Skills/ Intelligence', 'percentage' => 25],
                ],
            ], */
        ];

        $status = 'scoring'; // que, scoring, done
        $completed = 0;
        // $score = $faker->biasedNumberBetween(1, $categoryCriteria->percentage);
        $score = 0;

        foreach ($contestants as $key => $contestant) {
            factory(Contestant::class)->create([
                'contest_id' => $contest->id,
                'number' => $key + 1,
                'name' => $contestant['name'],
                'description' => $contestant['description'],
                'picture' => Storage::putFile('profile_pictures', new File(public_path('images/avatar-contestant.png'))),
            ]);
        }

        foreach ($judges as $judge) {
            $contestJudge = factory(Judge::class)->create([
                'user_id' => factory(User::class)->create(['name' => $judge, 'username' => Str::slug($judge)])->id,
                'contest_id' => $contest->id,
            ]);

            $contestJudge->user->assign('judge');
        }

        foreach ($categories as $category) {
            $percentage = $contest->contestCategories()->sum('percentage');

            if (! $percentage) {
                $percentage = 50;
            }

            $contestCategory = ContestCategory::create([
                'contest_id' => $contest->id,
                'category_id' => factory(Category::class)->create(['name' => $category['name'], 'description' => ''])->id,
                'status' => $status,
                // 'percentage' => $faker->biasedNumberBetween(10, ($percentage - 10)),
                'percentage' => $category['percentage'],
            ]);

            foreach ($category['criterias'] as $criteria) {
                $percentage = $contestCategory->categoryCriterias()->sum('percentage');

                if (! $percentage) {
                    $percentage = 50;
                }

                CategoryCriteria::create([
                    'contest_category_id' => $contestCategory->id,
                    'criteria_id' => factory(Criteria::class)->create(['name' => $criteria['name'], 'description' => ''])->id,
                    // 'percentage' => $faker->biasedNumberBetween(10, ($percentage - 10)),
                    'percentage' => $criteria['percentage'],
                ]);
            }
        }

        foreach ($contest->contestCategories as $categoryKey => $contestCategory) {
            foreach ($contest->contestants as $key => $contestant) {
                /* if (! $categoryKey && ! $key) {
                    continue;
                } */

                CategoryContestant::create([
                    'contest_category_id' => $contestCategory->id,
                    'contestant_id' => $contestant->id,
                ]);
            }

            foreach ($contest->judges as $key => $judge) {
                /* if (1 == $categoryKey && 1 == $key) {
                    continue;
                } */

                CategoryJudge::create([
                    'contest_category_id' => $contestCategory->id,
                    'judge_id' => $judge->id,
                    'completed' => $completed, // 1 = lock scores, 0 = pending scores
                ]);
            }

            foreach ($contestCategory->categoryCriterias as $categoryCriteria) {
                foreach ($contestCategory->categoryJudges as $categoryJudge) {
                    foreach ($contestCategory->categoryContestants as $categoryContestant) {
                        Score::create([
                            'score' => $score,
                            'contest_category_id' => $contestCategory->id,
                            'category_contestant_id' => $categoryContestant->id,
                            'category_judge_id' => $categoryJudge->id,
                            'category_criteria_id' => $categoryCriteria->id,
                        ]);
                    }
                }
            }
        }
    }
}
