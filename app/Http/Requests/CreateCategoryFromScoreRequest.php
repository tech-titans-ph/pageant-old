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
            'percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'contestant_count' => ['required', 'integer', 'min:1', 'max:' . $category->categoryContestants()->count()],
            'include_judges' => ['nullable'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'percentage' => 'Points',
            'contestant_count' => 'Number of Contestants',
        ];
    }
}
