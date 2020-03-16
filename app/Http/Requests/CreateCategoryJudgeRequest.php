<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCategoryJudgeRequest extends FormRequest
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
            'judge_id' => [
                'required',
                Rule::exists('judges', 'id')->where('contest_id', $contest->id),
                Rule::unique('category_judges')->where('category_id', $category->id),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'judge_id' => 'Judge',
        ];
    }
}
