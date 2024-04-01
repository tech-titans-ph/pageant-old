<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCategoryFromScoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $contest = $this->route('contest');

        $category = $contest->categories()->findOrFail($this->route('category'));

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where('contest_id', $contest->id)],
            'has_criterias' => ['boolean'],
            'scoring_system' => [
                'nullable',
                'required_with:has_criterias',
                Rule::in($contest->scoring_system == 'ranking' ? array_keys(config('options.scoring_systems')) : 'average'),
            ],
            'max_points_percentage' => [
                'nullable',
                Rule::requiredIf(($contest->scoring_system == 'ranking' && ! $this->has_criterias) || $contest->scoring_system == 'average'),
                'integer',
                'min:2',
                'max:100',
            ],
            'contestant_count' => ['required', 'integer', 'min:2', 'max:' . $category->contestants()->count()],
            'include_judges' => ['nullable'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'has_criterias' => 'Has Criterias',
            'scoring_system' => 'Scoring System',
            'max_points_percentage' => 'Maximum Points or Percentage',
            'contestant_count' => 'Number of Contestants',
        ];
    }
}
