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
        $this->errorBag = "{$this->column}_{$this->value}";

        $columns = collect(['category_judge_id', 'category_contestant_id', 'criteria_id']);

        return [
            'column' => ['bail', 'required', 'in:' . $columns->implode(',')],
            'value' => ['bail', 'required', 'integer'],
            'auth_password' => [
                'bail',
                'nullable',
                Rule::requiredIf($columns->contains($this->column) ? Score::where($this->column, $this->value)->count() : false),
                'string',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'auth_password' => 'Password',
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
            if ($this->auth_password && (! Hash::check($this->auth_password, auth()->user()->password))) {
                $validator->errors()->add('auth_password', 'Invalid password.');

                return;
            }
        });
    }
}
