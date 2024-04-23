<?php

namespace App\Providers;

use Illuminate\Support\{Collection, ServiceProvider};

class RankingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Collection::macro('rankCriteriaContestants', function ($criteria, $category, $contest) {
            $rank = 1;

            $points = 0;

            return $this->transform(function ($contestant) use ($criteria, $category) {
                $judgeScores = $criteria->scores
                    ->where('category_contestant_id', '=', $contestant->pivot->id)
                    ->groupBy('category_judge_id')
                    ->transform(function ($judgeScores) use ($criteria, $category) {
                        $judgeScores = $judgeScores->where('criteria_id', '=', $criteria->id);

                        $pointsSum = $judgeScores->sum('points');

                        return [
                            'scores' => $judgeScores,
                            'points_sum' => $pointsSum,
                            'points_percentage' => $pointsSum / $category->criterias->sum('max_points_percentage') * $category->max_points_percentage,
                        ];
                    });

                $contestant->judge_scores = $judgeScores;

                $contestant->points_sum = $judgeScores->sum('points_sum');

                $contestant->average = $judgeScores->avg('points_sum');

                return $contestant;
            })->sortByDesc('average')->values()->transform(function ($contestant, $index) use (&$rank, &$points) {
                if ($index) {
                    if ($contestant->average != $points) {
                        ++$rank;

                        $points = $contestant->average;
                    }
                } else {
                    $points = $contestant->average;
                }

                $contestant->ranking = $rank;

                return $contestant;
            })->when($category->scoring_system == 'ranking', function (Collection $collection) {
                $rank = 1;

                $points = 0;

                return $collection->sortByDesc('points_sum')->values()->transform(function ($item, $index) use (&$rank, &$points) {
                    if ($index) {
                        if ($item->points_sum != $points) {
                            ++$rank;

                            $points = $item->average;
                        }
                    } else {
                        $points = $item->points_sum;
                    }

                    $item->ranking = $rank;

                    return $item;
                });
            });
        });

        Collection::macro('rankCategoryContestants', function ($category, $contest) {
            $rank = 1;

            $points = 0;

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
            })->sortByDesc('average')->values()->transform(function ($contestant, $index) use (&$rank, &$points) {
                if ($index) {
                    if ($contestant->average != $points) {
                        ++$rank;

                        $points = $contestant->average;
                    }
                } else {
                    $points = $contestant->average;
                }

                $contestant->ranking = $rank;

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

                    $group->transform(function ($score, $categoryContestantId) use ($groupId) {
                        return [
                            'category_contestant_id' => $categoryContestantId,
                            'group_id' => $groupId,
                            'points_sum' => $score->sum('points'),
                        ];
                    })->sortByDesc('points_sum')
                        ->values()
                        ->each(function ($rankedContestant, $index) use (&$collection, &$rank, &$points) {
                            $contestant = $collection->filter(function ($collectionContestant) use ($rankedContestant) {
                                return $collectionContestant->pivot->id == $rankedContestant['category_contestant_id'];
                            })->first();

                            if ($index) {
                                if ($rankedContestant['points_sum'] != $points) {
                                    ++$rank;

                                    $points = $rankedContestant['points_sum'];
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

                $rank = 1;

                $points = 0;

                return $collection->transform(function ($item) {
                    $item->rank_sum = $item->ranks->sum('rank');

                    return $item;
                })->sortBy('rank_sum'/* [['rank_sum', 'asc'], ['average', 'desc']] */)->values()->transform(function ($item, $index) use (&$rank, &$points) {
                    if ($index) {
                        if ($item->rank_sum != $points) {
                            ++$rank;

                            $points = $item->rank_sum;
                        }
                    } else {
                        $points = $item->rank_sum;
                    }

                    $item->ranking = $rank;

                    return $item;
                });
            })->when($contest->scoring_system == 'ranking' && $category->scoring_system == 'average', function (Collection $collection) use ($category) {
                $rankedScores = collect([]);

                $category->judges->each(function ($judge) use ($category, $rankedScores) {
                    $rank = 1;

                    $points = 0;

                    $rankedScore = $category->scores
                        ->where('category_id', '=', $category->id)
                        ->where('category_judge_id', '=', $judge->pivot->id)
                        ->groupBy('category_contestant_id')->transform(function ($contestant, $index) use ($category, $judge) {
                            return [
                                'category_judge_id' => $judge->pivot->id,
                                'judge_id' => $judge->id,
                                'category_contestant_id' => $index,
                                'contestant_id' => $category->contestants->firstWhere('pivot.id', '=', $index)->id,
                                'points_sum' => $contestant->sum('points'),
                                'rank' => 0,
                            ];
                        })->sortByDesc('points_sum')->values()->transform(function ($contestant, $index) use (&$rank, &$points) {
                            if ($index) {
                                if ($contestant['points_sum'] != $points) {
                                    ++$rank;

                                    $points = $contestant['points_sum'];
                                }
                            } else {
                                $points = $contestant['points_sum'];
                            }

                            $contestant['rank'] = $rank;

                            return $contestant;
                        });

                    $rankedScores->push($rankedScore);
                });

                $category->ranked_scores = $rankedScores->flatten(1);

                $rank = 1;

                $points = 0;

                return $collection->transform(function ($item) use ($category) {
                    $item->rank_sum = $category->ranked_scores
                        ->where('contestant_id', '=', $item->id)
                        ->sum('rank');

                    return $item;
                })->sortBy('rank_sum')->values()->transform(function ($item, $index) use (&$rank, &$points) {
                    if ($index) {
                        if ($item->rank_sum != $points) {
                            ++$rank;

                            $points = $item['rank_sum'];
                        }
                    } else {
                        $points = $item['rank_sum'];
                    }

                    $item['ranking'] = $rank;

                    return $item;
                });
            });
        });

        Collection::macro('rankContestants', function ($contest) {
            $rank = 1;

            $points = 0;

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
            })->sortByDesc('average_sum')->values()->transform(function ($contestant, $index) use (&$rank, &$points) {
                if ($index) {
                    if ($contestant->average_sum != $points) {
                        ++$rank;

                        $points = $contestant->average_sum;
                    }
                } else {
                    $points = $contestant->average_sum;
                }

                $contestant->ranking = $rank;

                return $contestant;
            })->when($contest->scoring_system == 'ranking', function (Collection $collection) use ($contest) {
                $collection->transform(function ($item) {
                    $item->ranks = collect();

                    $item->ranking = 0;

                    return $item;
                });

                $contest->categories->each(function ($category) use ($collection) {
                    if ($category->scoring_system == 'average') {
                        $category->ranked_contestants->each(function ($rankedContestant, $index) use (&$rank, &$points, $collection, $category) {
                            $contestant = $collection->filter(function ($contestant) use ($rankedContestant) {
                                return $contestant->id == $rankedContestant->id;
                            })->first();

                            $contestant->ranks->push([
                                'category_id' => $category->id,
                                'rank' => $rankedContestant->ranking,
                            ]);

                            return $rankedContestant;
                        });
                    } else {
                        $category->scores->groupBy([
                            $category->has_criterias ? 'criteria_id' : 'category_id',
                            'category_contestant_id',
                        ])->each(function ($group, $groupId) use ($category, $collection) {
                            $rank = 1;

                            $points = 0;

                            $categoryContestants = $group->transform(function ($score, $categoryContestantId) use ($groupId, $category) {
                                $contestant = $category->contestants->filter(function ($categoryContestant) use ($categoryContestantId) {
                                    return $categoryContestant->pivot->id == $categoryContestantId;
                                })->first();

                                return (object) [
                                    'contestant_id' => $contestant->id,
                                    'category_id' => $category->id,
                                    'group_id' => $groupId,
                                    'points_sum' => $score->sum('points'),
                                    'ranks' => collect(),
                                    'rank_sum' => 0,
                                    'ranking' => 0,
                                ];
                            })->sortByDesc('points_sum')
                                ->values()
                                ->each(function ($categoryContestant, $index) use (&$rank, &$points) {
                                    if ($index) {
                                        if ($categoryContestant->points_sum != $points) {
                                            ++$rank;

                                            $points = $categoryContestant->points_sum;
                                        }
                                    } else {
                                        $points = $categoryContestant->points_sum;
                                    }

                                    $categoryContestant->ranks->push([
                                        'group_id' => $categoryContestant->group_id,
                                        'rank' => $rank,
                                    ]);
                                });

                            $rank = 1;

                            $points = 0;

                            $categoryContestants->transform(function ($categoryContestant) {
                                $categoryContestant->rank_sum = $categoryContestant->ranks->sum('rank');

                                return $categoryContestant;
                            })->sortBy('rank_sum')->values()->transform(function ($item, $index) use (&$rank, &$points, $collection) {
                                $contestant = $collection->filter(function ($contestant) use ($item) {
                                    return $contestant->id == $item->contestant_id;
                                })->first();

                                if ($index) {
                                    if ($item->rank_sum != $points) {
                                        ++$rank;

                                        $points = $item->rank_sum;
                                    }
                                } else {
                                    $points = $item->rank_sum;
                                }

                                $item->ranking = $rank;

                                $contestant->ranks->push([
                                    'category_id' => $item->category_id,
                                    'group_id' => $item->group_id,
                                    'rank' => $rank,
                                ]);

                                return $item;
                            });
                        });
                    }
                });

                $rank = 1;

                $points = 0;

                return $collection->transform(function ($item) {
                    $item->rank_sum = $item->ranks->sum('rank');

                    return $item;
                })->sortBy('rank_sum'/* [['rank_sum', 'asc'], ['average_sum', 'desc']] */)->values()->transform(function ($item, $index) use (&$rank, &$points) {
                    if ($index) {
                        if ($item->rank_sum != $points) {
                            ++$rank;

                            $points = $item->rank_sum;
                        }
                    } else {
                        $points = $item->rank_sum;
                    }

                    $item->ranking = $rank;

                    return $item;
                });
            });
        });
    }
}
