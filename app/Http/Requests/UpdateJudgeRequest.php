<?php

namespace App\Http\Requests;

use App\Judge;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJudgeRequest extends FormRequest
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

        $judge = $contest->judges()->findOrFail($this->route('judge'));

        if (request()->has('user_id')) {
            return [
                'user_id' => [
                    'required',
                    Rule::unique('judges')->ignore($judge)->where('contest_id', $contest->id),
                    Rule::exists('users', 'id')->where(function ($query) {
                        $user = User::whereIs('judge')->where('id', request()->input('user_id'))->first();

                        $query->where('id', $user->id ?? '');
                    }),
                ],
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255', Rule::unique('users')],
            ];
        }
    }

    public function attributes()
    {
        return [
            'user_id' => 'Selected Judge',
            'name' => 'Judge Name',
        ];
    }
}
