<?php

use App\{Category, CategoryContestant, CategoryJudge, Contest, Contestant, Criteria, Judge, Score, User};
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Artisan, Storage};

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker)
    {
        Artisan::call('migrate:fresh --seed');

        $this->scenario('average', true, 'average');
        // $this->scenario('average');

        // $this->scenario('ranking', true, 'average');

        // $this->scenario('ranking', true, 'ranking');
        // $this->scenario('ranking');

        return;

        Category::first()->update(['status' => 'scoring']);

        $query = Category::with(['contest'])
            ->where('status', 'scoring')
            ->has('judges')
            ->whereDoesntHave('judges', function (Illuminate\Database\Eloquent\Builder $query) {
                $query->where('completed', 0);
            });

        $query->dd();
    }

    public function scenario($categoryScoringSystem, $hasCriterias = false, $criteriaScoringSystem = null)
    {
        if (! $hasCriterias) {
            $criteriaScoringSystem = null;
        }

        $contest = factory(Contest::class)->create([
            'scoring_system' => $categoryScoringSystem,
        ]);

        $judges = $contest->judges()->createMany(
            factory(Judge::class, 3)->make(['contest_id' => $contest->id])->toArray()
        )->each(function ($judge, $index) {
            $judge->update(['order' => $index + 1]);
        });

        $contestants = $contest->contestants()->createMany(
            factory(Contestant::class, 3)->make([
                'contest_id' => $contest->id,
                'avatar' => UploadedFile::fake()->image('avatar.png')->store("{$contest->id}/contestants"),
            ])->toArray()
        )->each(function ($contestant, $index) {
            $contestant->update(['order' => $index + 1]);
        });

        $contest->categories()->createMany(
            factory(Category::class, 3)->make([
                'contest_id' => $contest->id,
                'has_criterias' => $hasCriterias,
                'scoring_system' => $criteriaScoringSystem,
                'status' => 'done',
            ])->toArray()
        )->each(function ($category, $index) use ($judges, $contestants) {
            $category->update(['order' => $index + 1]);

            $judges = $category->judges()->attach($judges->pluck('id'));

            $category->contestants()->attach($contestants->pluck('id'));

            if ($category->has_criterias) {
                $category->criterias()->createMany(
                    factory(Criteria::class, 3)->make(['category_id' => $category->id])->toArray()
                )->each(function ($criteria, $index) {
                    $criteria->update(['order' => $index + 1]);
                });
            }

            $category->judges()->get()->each(function ($judge, $index) use ($category) {
                $category->judges()->updateExistingPivot($judge->id, ['order' => $index + 1, 'completed' => true]);

                $category->contestants()->get()->each(function ($contestant, $index) use ($category, $judge) {
                    $category->contestants()->updateExistingPivot($contestant->id, ['order' => $index + 1]);

                    if ($category->has_criterias) {
                        $category->criterias()->get()->each(function ($criteria) use ($category, $judge, $contestant) {
                            Score::create([
                                'category_id' => $category->id,
                                'criteria_id' => $criteria->id,
                                'category_judge_id' => $judge->pivot->id,
                                'category_contestant_id' => $contestant->pivot->id,
                                'points' => random_int(10, $criteria->max_points_percentage),
                            ]);
                        });
                    } else {
                        Score::create([
                            'category_id' => $category->id,
                            'criteria_id' => null,
                            'category_judge_id' => $judge->pivot->id,
                            'category_contestant_id' => $contestant->pivot->id,
                            'points' => random_int(10, $category->max_points_percentage),
                        ]);
                    }
                });
            });
        });
    }

    public function oldTest(Faker $faker)
    {
        Storage::deleteDirectory('logos');
        Storage::deleteDirectory('profile-pictures');

        User::truncate();
        Contest::truncate();
        Contestant::truncate();
        Judge::truncate();
        Category::truncate();
        Criteria::truncate();
        CategoryContestant::truncate();
        CategoryJudge::truncate();
        CategoryScore::truncate();
        CriteriaScore::truncate();

        $judgeCount = 3;

        $contestantCount = 3;

        $categoryCount = 3;

        $criteriaPerCategoryCount = 3;

        $status = 'scoring'; // que, scoring, done

        $admin = factory(User::class)->states('admin')->create(['name' => 'Administrator', 'username' => 'admin']);

        $contest = factory(Contest::class)->create();

        $contest->judges()->createMany(
            factory(Judge::class, $judgeCount)->make(['contest_id' => $contest->id])->toArray()
        );

        foreach (range(1, $contestantCount) as $i) {
            factory(Contestant::class)->create(['contest_id' => $contest->id, 'number' => $i]);
        }

        foreach (range(1, $categoryCount) as $i) {
            $percentage = $faker->numberBetween(1, floor(100 / $categoryCount));

            if ($i === $categoryCount) {
                $percentage = 100 - $contest->categories()->sum('percentage');
            }

            factory(Category::class)->create([
                'contest_id' => $contest->id,
                'percentage' => $percentage,
                'status' => $status,
            ]);
        }

        foreach ($contest->categories()->get() as $category) {
            foreach (range(1, $criteriaPerCategoryCount) as $i) {
                $percentage = $faker->numberBetween(1, floor(100 / $criteriaPerCategoryCount));

                if ($i === $criteriaPerCategoryCount) {
                    $percentage = 100 - $category->criterias()->sum('percentage');
                }

                factory(Criteria::class)->create([
                    'category_id' => $category->id,
                    'percentage' => $percentage,
                ]);
            }

            foreach ($contest->contestants()->get() as $contestant) {
                $category->categoryContestants()->create([
                    'contestant_id' => $contestant->id,
                ]);
            }

            foreach ($contest->judges()->get() as $judge) {
                $categoryJudge = $category->categoryJudges()->create([
                    'judge_id' => $judge->id,
                    'completed' => $category->status === 'done' ? 1 : 0,
                ]);

                foreach ($category->categoryContestants()->get() as $categoryContestant) {
                    $categoryScore = $categoryJudge->categoryScores()->create([
                        'category_id' => $category->id,
                        'category_contestant_id' => $categoryContestant->id,
                    ]);

                    foreach ($category->criterias()->get() as $criteria) {
                        $categoryScore->criteriaScores()->create([
                            'criteria_id' => $criteria->id,
                            'score' => $category->status === 'que' ? 0 : $faker->numberBetween(1, $criteria->percentage),
                        ]);
                    }
                }
            }
        }

        return;

        $contest->load([
            'contestants',
            'judges',
            'categories',
            'categories.criterias',
            'categories.categoryContestants',
            'categories.categoryJudges',
            'categories.categoryJudges.categoryScores',
            'categories.categoryJudges.categoryScores.criteriaScores',
            'categories.categoryScores',
            'categories.categoryScores.criteriaScores',
        ]);

        dd($contest->toArray());
    }
}
