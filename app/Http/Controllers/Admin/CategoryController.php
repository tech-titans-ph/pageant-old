<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateCategoryFromScoreRequest, CreateCategoryRequest, UpdateCategoryRequest};
use App\Managers\ContestManager;
use App\{Category, Contest};
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index()
    {
        $categories = Category::when(request()->has('search-keyword'), function ($query) {
            return $query->where('name', 'like', '%' . request()->query('search-keyword') . '%');
        })
            ->select('name')
            ->groupBy('name')
            ->oldest('name')
            ->get();

        return response()->json($categories);
    }

    public function show(Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        $status = [
            'que' => 'Pending',
            'scoring' => 'Scoring',
            'done' => 'Completed',
        ];

        $removedContestants = $contest->contestants()->whereDoesntHave('categories', function (Builder $query) use ($category) {
            $query->where('category_id', $category->id);
        })->orderBy('order')->get();

        $removedJudges = $contest->judges()->whereDoesntHave('categories', function (Builder $query) use ($category) {
            $query->where('category_id', $category->id);
        })->orderBy('order')->get();

        $scoredCategoryContestants = [];

        if ($category->status === 'done') {
            $scoredCategoryContestants = $this->contestManager->getScoredCategoryContestants($category);
        }

        return view('admin.categories.show', compact('contest', 'category', 'status', 'removedContestants', 'removedJudges', 'scoredCategoryContestants'));
    }

    public function store(CreateCategoryRequest $request, Contest $contest)
    {
        $category = $this->contestManager->addCategory($contest, $request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])
            ->with('success', 'Category has been Created.');
    }

    public function update(UpdateCategoryRequest $request, Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        $this->contestManager->editCategory($category, $request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => request()->input('activeTab')])
            ->with('success', 'Category has been Edited.');
    }

    public function destroy(Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        $redirects = [
            'contest' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories']),
            'category' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id]),
        ];

        $page = request()->query('redirect') ?? 'contest';

        if ($category->criterias()->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not delete category. Please make sure that there are no criterias in this category.');
        }

        if ($category->categoryJudges()->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not delete category. Please make sure that there are no judges in this category.');
        }

        if ($category->categoryContestants()->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not delete category. Please make sure that there are no contestants in this category.');
        }

        $this->contestManager->removeCategory($category);

        return redirect($redirects['contest'])
            ->with('success', 'Category has been Deleted.');
    }

    public function start(Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        $redirects = [
            'contest' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories']),
            'category' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id]),
        ];

        $page = request()->query('redirect') ?? 'contest';

        if ($contest->categories()->where('status', 'scoring')->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not Start Scores. Please make sure that there is no other category that has started scoring.');
        }

        if (! $category->criterias()->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not Start Scores. Please make sure that this category has Criteria.');
        }

        if (! $category->categoryJudges()->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not Start Scores. Please make sure that this category has Judge.');
        }

        if (! $category->categoryContestants()->first()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not Start Scores. Please make sure that this category has Contestant.');
        }

        if ($category->status === 'scoring') {
            return redirect($redirects[$page])
                ->with('error', 'Could not Start Scores. Please make sure that this category has not started scoring.');
        }

        $this->contestManager->startCategory($category);

        return redirect($redirects[$page])
            ->with('success', 'Category has been started scoring.');
    }

    public function finish(Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        $redirects = [
            'contest' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories']),
            'category' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id]),
        ];

        $page = request()->query('redirect') ?? 'contest';

        if ($category->status !== 'scoring') {
            return redirect($redirects[$page])
                ->with('error', 'Could not Finish Scores. Please make sure that this category has started scoring.');
        }

        if ($category->categoryJudges()->where('completed', 0)->count()) {
            return redirect($redirects[$page])
                ->with('error', 'Could not Finish Scores. Please make sure that the judges in this category has completed scoring.');
        }

        $this->contestManager->finishCategory($category);

        $redirects['category'] = route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Scores']);

        return redirect($redirects[$page])
            ->with('success', 'Category has finished scoring.');
    }

    public function print(Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        abort_unless($category->status === 'done', 403, 'Could not print scores. Please make sure that this category has finished scoring.');

        $scoredCategoryContestants = $this->contestManager->getScoredCategoryContestants($category);

        return view('admin.categories.print', compact('contest', 'category', 'scoredCategoryContestants'));
    }

    public function live(Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        $categoryContestants = $this->contestManager->getScoredCategoryContestants($category);

        $categoryContestants = collect($categoryContestants->map(function ($categoryContestant, $index) use ($category) {
            $categoryJudges = $category->categoryJudges()
                ->with(['judge.user'])
                ->orderBy('judge_id')
                ->get()
                ->map(function ($categoryJudge) use ($category, $categoryContestant) {
                    $categoryScore = $categoryJudge->categoryScores()->where('category_contestant_id', $categoryContestant->id)->first();

                    $criteriaScores = $category->criterias()->get()->map(function ($criteria) use ($categoryScore) {
                        if ($categoryScore) {
                            $criteriaScore = $categoryScore->criteriaScores()->where('criteria_id', $criteria->id)->first();

                            if ($criteriaScore) {
                                $criteria['score'] = $criteriaScore->score;

                                return $criteria;
                            }
                        }

                        $criteria['score'] = 0;

                        return $criteria;
                    });

                    $categoryJudge['criteriaScores'] = $criteriaScores;

                    $categoryJudge['total'] = $categoryJudge['criteriaScores']->sum('score');

                    $categoryJudge['percentage'] = ($categoryJudge['total'] / $category->criterias()->sum('percentage')) * $category->percentage;

                    return $categoryJudge;
                });

            $categoryContestant->contestant->picture_url = $categoryContestant->contestant->picture_url;

            $categoryContestant['categoryJudges'] = $categoryJudges;

            return $categoryContestant;
        }))->sortByDesc('averageTotal')->values()->all();

        return response()->json([
            'criterias' => $category->criterias()->get(),
            'categoryContestants' => $categoryContestants,
        ]);
    }

    public function storeFromScore(Contest $contest, $category, CreateCategoryFromScoreRequest $request)
    {
        $category = $contest->categories()->findOrFail($category);

        if ($category->status !== 'done') {
            return redirect()
                ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Create Category from Results'])
                ->with('error', 'Could not create category from results. Please make sure that this category has finished scoring.');
        }

        $category = $this->contestManager->createCategoryFromScore($category, $request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])
            ->with('success', 'Category has been created from Results.');
    }
}
