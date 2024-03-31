<?php

namespace App\Providers;

use Illuminate\Support\{Collection, ServiceProvider};

class RankingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Collection::macro('rankCategoryContestants', function ($category, $contest) {
            return $this->transform(function ($contestant) use ($category, $contest) {
                $judgeScores = $category->scores
                    ->where('category_contestant_id', '=', $contestant->pivot->id)
                    ->groupBy('category_judge_id')
                    ->transform(function ($judgeScores) use ($category) {
                        $pointsSum = $judgeScores->sum('points');

                        return [
                            'scores' => $judgeScores,
                            'points_sum' => $pointsSum,
                            'points_percentage' => $category->has_criterias
                                ? $pointsSum / $category->criterias->sum('max_points_percentage') * $category->max_points_percentage
                                : $pointsSum,
                        ];
                    });

                $contestant->judge_scores = $judgeScores;

                $contestant->average = $judgeScores->avg($category->scoring_system == 'average' && $contest->scoring_system == 'ranking' ? 'points_sum' : 'points_percentage');

                return $contestant;
            })->sortByDesc('average')->values()->transform(function ($contestant, $index) {
                $contestant->ranking = $index + 1;

                return $contestant;
            })->when($category->scoring_system == 'ranking', function (Collection $collection) use ($category) {
                $collection->transform(function ($item) {
                    $item->ranks = collect();

                    return $item;
                });

                $category->scores->groupBy([
                    $category->has_criterias ? 'criteria_id' : 'category_id',
                    'category_contestant_id',
                ])->each(function ($group, $groupId) use ($collection) {
                    $rank = 1;

                    $points = 0;

                    $group->transform(function ($contestant, $categoryContestantId) use ($groupId) {
                        return [
                            'category_contestant_id' => $categoryContestantId,
                            'group_id' => $groupId,
                            'points_sum' => $contestant->sum('points'),
                        ];
                    })->sortByDesc('points_sum')
                        ->values()
                        ->each(function ($rankedContestant, $index) use ($collection, &$rank, &$points) {
                            $contestant = $collection->firstWhere(function ($collectionContestant) use ($rankedContestant) {
                                return $collectionContestant->pivot->id == $rankedContestant['category_contestant_id'];
                            });

                            if ($index) {
                                if ($rankedContestant['points_sum'] != $points) {
                                    ++$rank;
                                }
                            } else {
                                $points = $rankedContestant['points_sum'];
                            }

                            $contestant->ranks->push([
                                'group_id' => $rankedContestant['group_id'],
                                'rank' => $rank,
                            ]);
                        });
                });

                return $collection->transform(function ($item) {
                    $item->rank_sum = $item->ranks->sum('rank');

                    return $item;
                })->sortBy([['rank_sum', 'asc'], ['average', 'desc']])->values()->transform(function ($item, $index) {
                    $item->ranking = $index + 1;

                    return $item;
                });
            });
        });

        Collection::macro('rankContestants', function ($contest) {
            return $this->transform(function ($contestant) {
                $categoryScores = $contestant->categories->transform(function ($category) use ($contestant) {
                    $categoryJudgeScores = $category->judges->transform(function ($judge) use ($category, $contestant) {
                        $score = $category->scores
                            ->where('category_contestant_id', '=', $category->pivot->id)
                            ->where('category_id', '=', $category->id)
                            ->where('category_judge_id', '=', $judge->pivot->id);

                        $pointsSum = $score->sum('points');

                        return [
                            'points' => $pointsSum,
                            'points_percentage' => $category->has_criterias
                                ? $pointsSum / $category->criterias->sum('max_points_percentage') * $category->max_points_percentage
                                : $pointsSum,
                            'category_judge_id' => $judge->pivot->id,
                            'judge_id' => $judge->id,
                            'category_contestant_id' => $category->pivot->id,
                            'contestant_id' => $contestant->id,
                        ];
                    });

                    $category->judge_scores = collect($categoryJudgeScores);

                    $category->average = $category->judge_scores->avg('points_percentage');

                    return $category;
                });

                $contestant->category_scores = collect($categoryScores);

                $contestant->average_sum = $contestant->category_scores->sum('average');

                return $contestant;
            })->sortByDesc('average_sum')->values()->transform(function ($contestant, $index) {
                $contestant->ranking = $index + 1;

                return $contestant;
            })->when($contest->scoring_system == 'ranking', function (Collection $collection) use ($contest) {
                $collection->transform(function ($item) {
                    $item->ranks = collect();

                    $item->ranking = 0;

                    return $item;
                });

                $contest->categories->each(function ($category) use ($collection) {
                    if ($category->scoring_system == 'average') {
                        $category->ranked_contestants->each(function ($rankedContestant) use ($collection, $category) {
                            $contestant = $collection->firstWhere(function ($contestant) use ($rankedContestant) {
                                return $contestant->id == $rankedContestant->id;
                            });

                            $contestant->ranks->push([
                                'category_id' => $category->id,
                                'group_id' => $category->id,
                                'rank' => $contestant->ranking,
                            ]);
                        });
                    } else {
                        $category->scores->groupBy([
                            $category->has_criterias ? 'criteria_id' : 'category_id',
                            'category_contestant_id',
                        ])->each(function ($group, $groupId) use ($collection, $category) {
                            $rank = 1;

                            $points = 0;

                            $group->transform(function ($score, $categoryContestantId) use ($groupId, $category) {
                                $contestant = $category->contestants->firstWhere(function ($categoryContestant) use ($categoryContestantId) {
                                    return $categoryContestant->pivot->id == $categoryContestantId;
                                });

                                return [
                                    'contestant_id' => $contestant->id,
                                    'category_id' => $category->id,
                                    'group_id' => $groupId,
                                    'points_sum' => $score->sum('points'),
                                ];
                            })->sortByDesc('points_sum')
                                ->values()
                                ->each(function ($rankedContestant, $index) use ($collection, &$rank, &$points, $category) {
                                    $contestant = $collection->firstWhere(function ($collectionContestant) use ($rankedContestant) {
                                        return $collectionContestant->id == $rankedContestant['contestant_id'];
                                    });

                                    if ($index) {
                                        if ($rankedContestant['points_sum'] != $points) {
                                            ++$rank;
                                        }
                                    } else {
                                        $points = $rankedContestant['points_sum'];
                                    }

                                    $contestant->ranks->push([
                                        'category_id' => $category->id,
                                        'group_id' => $rankedContestant['group_id'],
                                        'rank' => $rank,
                                    ]);
                                });
                        });
                    }
                });

                $collection->transform(function ($item) {
                    $categoryRanks = $item->ranks->groupBy('category_id')->transform(function ($categoryRanks, $categoryId) {
                        return [
                            'category_id' => $categoryId,
                            'rank_sum' => $categoryRanks->sum('rank'),
                        ];
                    })->values();

                    $item->ranks = $categoryRanks;

                    return $item;
                });

                $contest->categories->each(function ($category) use ($collection) {
                    $collection->sortBy(function ($item) use ($category) {
                        return collect($item->ranks)->firstWhere('category_id', '=', $category->id)['rank_sum'];
                    })->values()->each(function ($item, $index) use ($category) {
                        $ranks = $item->ranks;

                        $rank = $ranks->firstWhere('category_id', '=', $category->id);

                        $rank['ranking'] = $index + 1;

                        $ranks = $ranks
                            ->where('category_id', '!=', $category->id)
                            ->merge([$rank]);

                        $item->ranks = $ranks;
                    });
                });

                return $collection->transform(function ($item) {
                    $item->rank_sum = $item->ranks->sum('ranking');

                    return $item;
                })->sortBy([['rank_sum', 'asc'], ['average_sum', 'desc']])->values()->transform(function ($item, $index) {
                    $item->ranking = $index + 1;

                    return $item;
                });
            });
        });
    }
}
