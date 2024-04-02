<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetScoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('judge')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $category = $this->route('category');

        $maxPointsPercentage = 1;

        if ($category->has_criterias) {
            $group = $category->criterias()->find(request()->input('group_id') ?? 0);
        } else {
            $group = $category;
        }

        if ($group) {
            $maxPointsPercentage = $group->max_points_percentage;
        }

        return [
            'group_id' => [
                'required',
                Rule::exists($group->getTable(), 'id')->where(function ($query) use ($category) {
                    $query->when($category->has_criterias, function ($query) use ($category) {
                        return $query->where('category_id', $category->id);
                    });
                }),
            ],
            'points' => [
                Rule::requiredIf(function () use ($group) {
                    return $group;
                }),
                'integer',
                'min:1',
                'max:' . $maxPointsPercentage,
            ],
        ];
    }
}
