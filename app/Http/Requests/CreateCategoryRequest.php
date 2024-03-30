<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCategoryRequest extends FormRequest
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

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('contest_id', $contest->id),
            ],
            'has_criterias' => ['boolean'],
            'scoring_system' => [
                'nullable',
                'required_if:has_criterias,true',
                Rule::in($contest->scoring_system == 'ranking' ? array_keys(config('options.scoring_systems')) : 'average'),
            ],
            'max_points_percentage' => [
                'nullable',
                Rule::requiredIf(! ($this->has_criterias && ($this->scoring_system == 'ranking' || $contest->scoring_system == 'ranking'))),
                'integer',
                'min:2',
                'max:100',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'percentage' => 'Percentage',
        ];
    }
}
