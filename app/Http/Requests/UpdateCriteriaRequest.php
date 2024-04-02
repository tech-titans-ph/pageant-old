<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCriteriaRequest extends FormRequest
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

        $criteria = $category->criterias()->findOrFail($this->route('criteria'));

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('criterias')->ignore($criteria)->where('category_id', $category->id)],
            'max_points_percentage' => ['required', 'integer', 'min:2', 'max:100'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'max_points_percentage' => 'Maximum Points or Percentage',
        ];
    }
}
