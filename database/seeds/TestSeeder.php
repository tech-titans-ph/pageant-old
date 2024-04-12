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

        /* $this->deleteContest(10);

        return; */

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
            factory(Judge::class, 4)->make(['contest_id' => $contest->id])->toArray()
        )->each(function ($judge, $index) {
            $judge->update(['order' => $index + 1]);
        });

        $contestants = $contest->contestants()->createMany(
            factory(Contestant::class, 12)->make([
                'contest_id' => $contest->id,
                'avatar' => UploadedFile::fake()->image('avatar.png')->store("{$contest->id}/contestants"),
            ])->toArray()
        )->each(function ($contestant, $index) {
            $contestant->update(['order' => $index + 1]);
        });

        $contest->categories()->createMany(
            factory(Category::class, 4)->make([
                'contest_id' => $contest->id,
                'has_criterias' => $hasCriterias,
                'scoring_system' => $criteriaScoringSystem,
                'status' => 'que',
            ])->toArray()
        )->each(function ($category, $index) use ($judges, $contestants) {
            $category->update(['order' => $index + 1]);

            $judges = $category->judges()->attach($judges->pluck('id'));

            $category->contestants()->attach($contestants->pluck('id'));

            if ($category->has_criterias) {
                $category->criterias()->createMany(
                    factory(Criteria::class, 2)->make(['category_id' => $category->id])->toArray()
                )->each(function ($criteria, $index) {
                    $criteria->update(['order' => $index + 1]);
                });
            }

            $category->judges()->get()->each(function ($judge, $index) use ($category) {
                $category->contestants()->get()->each(function ($contestant, $index) use ($category, $judge) {
                    /* if ($category->has_criterias) {
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
                    } */
                });
            });
        });
    }

    public function deleteContest($id)
    {
        $contest = Contest::find($id);

        $contest->categories()->get()->each(function ($category) {
            $category->scores()->delete();

            $category->criterias()->delete();

            $category->judges()->detach($category->judges()->pluck('judges.id'));

            $category->contestants()->detach($category->contestants()->pluck('contestants.id'));

            $category->delete();
        });

        $contest->judges()->delete();

        $contest->contestants()->delete();

        $contest->delete();
    }
}
