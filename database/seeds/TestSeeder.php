<?php

use App\Category;
use App\CategoryContestant;
use App\CategoryJudge;
use App\CategoryScore;
use App\Contest;
use App\Contestant;
use App\Criteria;
use App\CriteriaScore;
use App\Judge;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
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
                    'completed' => 'done' === $category->status ? 1 : 0,
                ]);

                foreach ($category->categoryContestants()->get() as $categoryContestant) {
                    $categoryScore = $categoryJudge->categoryScores()->create([
                        'category_id' => $category->id,
                        'category_contestant_id' => $categoryContestant->id,
                    ]);

                    foreach ($category->criterias()->get() as $criteria) {
                        $categoryScore->criteriaScores()->create([
                            'criteria_id' => $criteria->id,
                            'score' => 'que' === $category->status ? 0 : $faker->numberBetween(1, $criteria->percentage),
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
