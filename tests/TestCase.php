<?php

namespace Tests;

use App\Category;
use App\Contest;
use App\Contestant;
use App\Criteria;
use App\Judge;
use App\Managers\ContestManager;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    protected function login($user = null, $role = 'admin')
    {
        if (! $user) {
            $user = factory(User::class)->create();
            $user->assign($role);
        }

        $this->actingAs($user);

        return $user;
    }

    protected function contestFactory($judgeCount = 1, $contestantCount = 1, $categoryCount = 1, $criteriaPerCategoryCount = 1, $status = 'que')
    {
        $contestManager = new ContestManager();

        $contest = factory(Contest::class)->create();

        if ($judgeCount > 0) {
            factory(Judge::class, $judgeCount)->create([
                'contest_id' => $contest->id,
            ]);
        }

        if ($contestantCount > 0) {
            factory(Contestant::class, $contestantCount)->create([
                'contest_id' => $contest->id,
            ]);
        }

        foreach (range(1, $categoryCount) as $i) {
            $contestManager->addCategory(
                $contest,
                collect(factory(Category::class)->make(['contest_id' => null]))->except(['contest_id'])->all()
            );
        }

        $contest->categories()->update(['status' => $status]);

        if ($criteriaPerCategoryCount > 0) {
            $contest->categories()->get()->each(function ($category) use ($criteriaPerCategoryCount, $status) {
                if ('done' === $status) {
                    $category->categoryJudges()->update(['completed' => 1]);
                }

                factory(Criteria::class, $criteriaPerCategoryCount)->create([
                    'category_id' => $category->id,
                ]);
            });
        }

        if ('que' !== $status) {
        }

        return $contest;
    }

    protected function contestFaker($judgeCount = 1, $contestantCount = 1, $categoryCount = 1, $criteriaPerCategoryCount = 1, $status = 'que')
    {
        $contest = factory(Contest::class)->create();

        if ($judgeCount > 0) {
            $contest->judges()->createMany(
                factory(Judge::class, $judgeCount)->make(['contest_id' => $contest->id])->toArray()
            );
        }

        foreach (range(1, $contestantCount) as $i) {
            factory(Contestant::class)->create(['contest_id' => $contest->id, 'number' => $i]);
        }

        foreach (range(1, $categoryCount) as $i) {
            $percentage = $this->faker->numberBetween(1, floor(100 / $categoryCount));

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
                $percentage = $this->faker->numberBetween(1, floor(100 / $criteriaPerCategoryCount));

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

                if ('que' !== $category->status) {
                    foreach ($category->categoryContestants()->get() as $categoryContestant) {
                        $categoryScore = $categoryJudge->categoryScores()->create([
                            'category_id' => $category->id,
                            'category_contestant_id' => $categoryContestant->id,
                        ]);

                        foreach ($category->criterias()->get() as $criteria) {
                            $categoryScore->criteriaScores()->create([
                                'criteria_id' => $criteria->id,
                                'score' => 'que' === $category->status ? 0 : $this->faker->numberBetween(1, $criteria->percentage),
                            ]);
                        }
                    }
                }
            }
        }

        return $contest;
    }
}
