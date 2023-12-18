<?php

namespace App\Managers;

use App\{Category, CategoryContestant, CategoryJudge, Contest, Contestant, Criteria, CriteriaScore, Judge, User};
use Illuminate\Http\File;
use Illuminate\Support\Facades\{Hash, Storage};
use Illuminate\Support\Str;

class ContestManager
{
    public function create($data)
    {
        $data['logo'] = Storage::put('logos', $data['logo']);

        return Contest::create($data);
    }

    public function update(Contest $contest, $data)
    {
        if (isset($data['logo'])) {
            Storage::delete($contest->logo);

            $data['logo'] = Storage::put('logos', $data['logo']);
        }

        $contest->update($data);

        return $contest;
    }

    public function delete(Contest $contest)
    {
        $contest->delete();

        Storage::delete($contest->logo);

        return $this;
    }

    public function addJudge(Contest $contest, $data)
    {
        if (isset($data['user_id'])) {
            $user = User::find($data['user_id']);
        } else {
            $user = User::create([
                'name' => $data['name'],
                'username' => Str::slug($data['name']),
                'password' => Hash::make('password'),
            ]);

            $user->assign('judge');
        }

        $judge = $contest->judges()->create([
            'user_id' => $user->id,
        ]);

        $this->addCategoryJudge($judge);

        return $judge;
    }

    public function editJudge(Judge $judge, $data)
    {
        if (isset($data['user_id'])) {
            $user = User::find($data['user_id']);
        } else {
            $user = User::create([
                'name' => $data['name'],
                'username' => Str::slug($data['name']),
                'password' => Hash::make('password'),
            ]);

            $user->assign('judge');
        }

        $judge->update(['user_id' => $user->id]);

        return $judge;
    }

    public function removeJudge(Judge $judge)
    {
        $judge->delete();

        return $this;
    }

    public function loginJudge(Judge $judge)
    {
        auth()->login($judge->user);

        session(['judge' => $judge->id]);

        return $this;
    }

    public function addContestant(Contest $contest, $data)
    {
        $data['picture'] = Storage::put('profile-pictures', $data['picture']);

        $contestant = $contest->contestants()->create($data);

        $this->addCategoryContestant($contestant);

        return $contestant;
    }

    public function editContestant(Contestant $contestant, $data)
    {
        if (isset($data['picture'])) {
            Storage::delete($contestant->picture);
            $data['picture'] = Storage::put('profile-pictures', $data['picture']);
        }

        $contestant->update($data);

        return $contestant;
    }

    public function removeContestant(Contestant $contestant)
    {
        $contestant->delete();

        Storage::delete($contestant->picture);

        return $this;
    }

    public function addCategory(Contest $contest, $data)
    {
        $category = $contest->categories()->create($data);

        $contest->judges()->get()->each(function ($judge) use ($category) {
            $category->categoryJudges()->create(['judge_id' => $judge->id]);
        });

        $contest->contestants()->get()->each(function ($contestant) use ($category) {
            $category->categoryContestants()->create(['contestant_id' => $contestant->id]);
        });

        return $category;
    }

    public function editCategory(Category $category, $data)
    {
        $category->update($data);

        return $category;
    }

    public function removeCategory(Category $category)
    {
        $category->delete();

        return $this;
    }

    public function startCategory(Category $category)
    {
        $category->update(['status' => 'scoring']);

        $category->categoryJudges()->update(['completed' => 0]);

        return $category;
    }

    public function finishCategory(Category $category)
    {
        $category->update(['status' => 'done']);

        return $category;
    }

    public function addCriteria(Category $category, $data)
    {
        return $category->criterias()->create($data);
    }

    public function editCriteria(Criteria $criteria, $data)
    {
        $criteria->update($data);

        return $criteria;
    }

    public function removeCriteria(Criteria $criteria)
    {
        CriteriaScore::where(['criteria_id' => $criteria->id])->delete();

        $criteria->delete();

        return $this;
    }

    public function addCategoryJudge(Judge $judge)
    {
        $judge->contest()->first()->categories()->get()->each(function ($category) use ($judge) {
            $judge->categoryJudges()->create(['category_id' => $category->id]);
        });

        return $this;
    }

    public function removeCategoryJudge(CategoryJudge $categoryJudge)
    {
        $categoryJudge->categoryScores()->get()->each(function ($categoryScore) {
            $categoryScore->criteriaScores()->delete();
            $categoryScore->delete();
        });

        $categoryJudge->delete();

        return $this;
    }

    public function addCategoryContestant(Contestant $contestant)
    {
        $contestant->contest()->first()->categories()->get()->each(function ($category) use ($contestant) {
            $contestant->categoryContestants()->create(['category_id' => $category->id]);
        });

        return $this;
    }

    public function removeCategoryContestant(CategoryContestant $categoryContestant)
    {
        $categoryContestant->categoryScores()->get()->each(function ($categoryScore) {
            $categoryScore->criteriaScores()->delete();
            $categoryScore->delete();
        });

        $categoryContestant->delete();

        return $this;
    }

    public function setScore(CategoryContestant $categoryContestant, Criteria $criteria, $score)
    {
        $judge = Judge::findOrFail(session('judge'));

        $category = $categoryContestant->category()->first();

        $categoryJudge = $category->categoryJudges()->where(['judge_id' => $judge->id])->firstOrFail();

        $categoryScore = $category->categoryScores()->firstOrCreate([
            'category_judge_id' => $categoryJudge->id,
            'category_contestant_id' => $categoryContestant->id,
        ]);

        return $categoryScore->criteriaScores()->updateOrCreate(
            ['criteria_id' => $criteria->id],
            ['score' => $score]
        );
    }

    public function completeScore(CategoryJudge $categoryJudge)
    {
        $categoryJudge->update(['completed' => 1]);

        return $categoryJudge;
    }

    public function getScoredCategoryContestants(Category $category)
    {
        return $category->categoryContestants()->get()->map(function ($categoryContestant) use ($category) {
            $total = 0;

            foreach ($categoryContestant->categoryScores()->get() as $categoryScore) {
                $total += $categoryScore->criteriaScores()->sum('score');
            }

            $averageTotal = $total / $category->categoryJudges()->count();
            $averagePercentage = ($averageTotal / $category->criterias()->sum('percentage')) * $category->percentage;

            $categoryContestant['total'] = $total;
            $categoryContestant['averageTotal'] = $averageTotal;
            $categoryContestant['averagePercentage'] = $averagePercentage;

            return $categoryContestant;
        })->sortByDesc('averageTotal');
    }

    public function getScoredContestants(Contest $contest)
    {
        return $contest->contestants()->get()->map(function ($contestant) {
            $totalPercentage = 0;
            $contestant['categoryTotals'] = 0;

            foreach ($contestant->categoryContestants as $categoryContestant) {
                $total = 0;

                foreach ($categoryContestant->categoryScores as $categoryScore) {
                    $total += $categoryScore->criteriaScores()->sum('score');
                }

                $contestant['categoryTotals'] += $total;

                $averageTotal = $total / $categoryContestant->category->categoryJudges()->count();
                $averagePercentage = ($averageTotal / $categoryContestant->category->criterias()->sum('percentage')) * $categoryContestant->category->percentage;

                $totalPercentage += $averagePercentage;
            }

            $contestant['totalPercentage'] = $totalPercentage;

            return $contestant;
        })/* ->sortByDesc('totalPercentage'); */
            ->sortByDesc('categoryTotals');
    }

    public function createCategoryFromScore($category, $data)
    {
        $newCategory = $category->contest()->first()->categories()->create(
            collect($data)->except(['contestant_count', 'include_judges'])->all()
        );

        $categoryContestants = collect($this->getScoredCategoryContestants($category)->toArray())->values()->all();

        $categoryContestants = collect($categoryContestants)->filter(function ($categoryContestant, $index) use ($data) {
            return $index < $data['contestant_count'];
        })->values()->all();

        foreach ($categoryContestants as $categoryContestant) {
            $newCategory->categoryContestants()->create(['contestant_id' => $categoryContestant['contestant_id']]);
        }

        if (isset($data['include_judges'])) {
            foreach ($category->contest()->first()->judges()->get() as $judge) {
                $newCategory->categoryJudges()->create(['judge_id' => $judge->id]);
            }
        }

        return $newCategory;
    }

    public function createContestFromScore($contest, $data)
    {
        $data['logo'] = Storage::put('logos', $data['logo']);

        $newContest = Contest::create(
            collect($data)->except(['contestant_count', 'include_judges'])->all()
        );

        $contestants = collect($this->getScoredContestants($contest)->toArray())->values()->all();

        $contestants = collect($contestants)->filter(function ($contestant, $index) use ($data) {
            return $index < $data['contestant_count'];
        })->values()->all();

        // sleep(1);

        foreach ($contestants as $contestant) {
            $newContest->contestants()->create([
                'name' => $contestant['name'],
                'description' => $contestant['description'],
                'number' => $contestant['number'],
                'picture' => Storage::putFile('profile-pictures', new File(Storage::path($contestant['picture']))),
            ]);
        }

        if (isset($data['include_judges'])) {
            foreach ($contest->judges()->get() as $judge) {
                $newContest->judges()->create([
                    'user_id' => $judge->user_id,
                ]);
            }
        }

        return $newContest;
    }
}
