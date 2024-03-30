<?php

namespace App\Managers;

use App\{Category, CategoryContestant, CategoryJudge, Contest, Contestant, Criteria, CriteriaScore, Judge, User};
use Illuminate\Http\File;
use Illuminate\Support\Facades\{Hash, Storage};

class ContestManager
{
    public function create($data)
    {
        $logo = $data['logo'];

        unset($data['logo']);

        $contest = Contest::create($data);

        $contest->update(['logo' => Storage::put("{$contest->id}/logo", $logo)]);

        return $contest;
    }

    public function update(Contest $contest, $data)
    {
        if (isset($data['scoring_system']) && $contest->categories()->whereHas('scores')->count()) {
            unset($data['scoring_system']);
        }

        if (isset($data['logo'])) {
            Storage::delete($contest->logo);

            $data['logo'] = Storage::put("{$contest->id}/logo", $data['logo']);
        }

        $contest->update($data);

        return $contest;
    }

    public function delete(Contest $contest)
    {
        Storage::delete($contest->logo);

        Storage::deleteDirectory($contest->id);

        $contest->delete();

        return $this;
    }

    public function addJudge(Contest $contest, $data)
    {
        $judge = $contest->judges()->create($data);

        $this->addCategoryJudge($judge);

        $judge->update(['order' => $contest->judges()->count()]);

        return $judge;
    }

    public function editJudge(Judge $judge, $data)
    {
        $judge->update($data);

        return $judge;
    }

    public function removeJudge(Judge $judge)
    {
        $contest = $judge->contest()->first();

        $judge->delete();

        $contest->judges()->orderBy('order')->get()->each(function ($judge, $index) {
            $judge->update(['order' => $index + 1]);
        });

        return $this;
    }

    public function loginJudge(Judge $judge)
    {
        auth('judge')->login($judge);

        session(['judge' => $judge->id]);

        return $this;
    }

    public function addContestant(Contest $contest, $data)
    {
        $data['avatar'] = Storage::put("{$contest->id}/contestants", $data['avatar']);

        $contestant = $contest->contestants()->create($data);

        $contestant->update(['order' => $contest->contestants()->count()]);

        $this->addCategoryContestant($contestant);

        return $contestant;
    }

    public function editContestant(Contestant $contestant, $data)
    {
        if (isset($data['avatar'])) {
            Storage::delete($contestant->avatar);

            $data['avatar'] = Storage::put("{$contestant->contest()->first()->id}/contestants", $data['avatar']);
        }

        $contestant->update($data);

        return $contestant;
    }

    public function removeContestant(Contestant $contestant)
    {
        $contest = $contestant->contest()->first();

        Storage::delete($contestant->avatar);

        $contestant->delete();

        $contest->contestants()->orderBy('order')->get()->each(function ($contestant, $index) {
            $contestant->update(['order' => $index + 1]);
        });

        return $this;
    }

    public function addCategory(Contest $contest, $data)
    {
        if (! ($data['has_criterias'] ?? false)) {
            unset($data['scoring_system']);
        }

        if ($data['has_criterias'] ?? false && ($contest->scoring_system == 'ranking' || ($data['scoring_system'] ?? '') == 'ranking')) {
            unset($data['max_points_percentage']);
        }

        $category = $contest->categories()->create($data);

        $category->update(['order' => $contest->categories()->count()]);

        $contest->judges()->get()->each(function ($judge) use ($category) {
            $category->judges()->attach($judge->id, ['order' => $category->judges()->count() + 1]);
        });

        $contest->contestants()->get()->each(function ($contestant) use ($category) {
            $category->contestants()->attach($contestant->id, ['order' => $category->contestants()->count() + 1]);
        });

        return $category;
    }

    public function editCategory(Category $category, $data)
    {
        if (collect($data)->only(['has_criterias', 'scoring_system', 'max_points_percentage'])->filter()->count() && $category->scores()->count()) {
            unset($data['has_criterias'], $data['scoring_system'], $data['max_points_percentage']);
        } else {
            if (! ($data['has_criterias'] ?? false)) {
                $data['scoring_system'] = null;
            }

            if ($data['has_criterias'] && ($category->contest->scoring_system == 'ranking' || ($data['scoring_system'] ?? '') == 'ranking')) {
                $data['max_points_percentage'] = null;
            }
        }

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
            $category->judges()->attach($judge->id, ['order' => $category->judges()->count() + 1]);
        });

        return $this;
    }

    public function removeCategoryJudge(CategoryJudge $categoryJudge)
    {
        $category = $categoryJudge->category()->first();

        $judge = $categoryJudge->judge()->first();

        $category->scores()->where('category_judge_id', $categoryJudge->id)->delete();

        $category->judges()->detach($judge->id);

        $categoryJudgeTable = $categoryJudge->getTable();

        $category->judges()->orderBy("{$categoryJudgeTable}.order")->each(function ($judge, $index) use ($category) {
            $category->judges()->updateExistingPivot($judge->id, ['order' => $index + 1]);
        });

        return $this;
    }

    public function addCategoryContestant(Contestant $contestant)
    {
        $contestant->contest()->first()->categories()->get()->each(function ($category) use ($contestant) {
            $category->contestants()->attach($contestant->id, ['order' => $category->contestants()->count() + 1]);
        });

        return $this;
    }

    public function removeCategoryContestant(CategoryContestant $categoryContestant)
    {
        $category = $categoryContestant->category()->first();

        $contestant = $categoryContestant->contestant()->first();

        $category->scores()->where('category_contestant_id', $categoryContestant->id)->delete();

        $category->contestants()->detach($contestant->id);

        $categoryContestantTable = $categoryContestant->getTable();

        $category->contestants()->orderBy("{$categoryContestantTable}.order")->each(function ($contestant, $index) use ($category) {
            $category->contestants()->updateExistingPivot($contestant->id, ['order' => $index + 1]);
        });

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

            $categoryContestant['averageTotal'] = $averageTotal;
            $categoryContestant['averagePercentage'] = $averagePercentage;

            return $categoryContestant;
        })->sortByDesc('averageTotal');
    }

    public function getScoredContestants(Contest $contest)
    {
        return $contest->contestants()->get()->map(function ($contestant) {
            $totalPercentage = 0;

            foreach ($contestant->categoryContestants as $categoryContestant) {
                $total = 0;

                foreach ($categoryContestant->categoryScores as $categoryScore) {
                    $total += $categoryScore->criteriaScores()->sum('score');
                }

                $averageTotal = $total / $categoryContestant->category->categoryJudges()->count();
                $averagePercentage = ($averageTotal / $categoryContestant->category->criterias()->sum('percentage')) * $categoryContestant->category->percentage;

                $totalPercentage += $averagePercentage;
            }

            $contestant['totalPercentage'] = $totalPercentage;

            return $contestant;
        })->sortByDesc('totalPercentage');
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
