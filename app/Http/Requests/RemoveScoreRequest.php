<?php

namespace App\Http\Requests;

use App\Score;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RemoveScoreRequest extends FormRequest
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
        return [
            'column' => ['required', 'in:category_judge_id,category_contestant_id,criteria_id'],
            'value' => ['required', 'integer'],
            'confirm_password' => [
                'bail',
                'nullable',
                Rule::requiredIf(Score::where($this->column, $this->value)->count()),
                'string',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'Password',
        ];
    }

    public function messages()
    {
        return [
            'column.*' => 'Invalid request.',
            'value.*' => 'Invalid request.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->password && (! Hash::check($this->password, auth()->user()->password))) {
                $validator->errors()->add('password', 'Invalid password.');

                return;
            }
        });
    }
}
