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
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $category = $this->route('category');

        $percentage = 1;

        $criteria = $category->criterias()->find(request()->input('criteria_id') ?? 0);

        if ($criteria) {
            $percentage = $criteria->percentage;
        }

        return [
            'criteria_id' => [
                'required',
                Rule::exists('criterias', 'id')->where(function ($query) use ($category) {
                    $query->where('category_id', $category->id);
                }),
            ],
            'score' => [
                Rule::requiredIf(function () use ($criteria) {
                    return $criteria;
                }),
                'integer',
                'min:1',
                'max:' . $percentage,
            ],
        ];
    }
}
