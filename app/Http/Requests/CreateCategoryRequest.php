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
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where('contest_id', $contest->id)],
            'percentage' => ['required', 'integer', 'min:1', 'max:100'],
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
