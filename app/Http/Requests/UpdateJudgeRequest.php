<?php

namespace App\Http\Requests;

use App\{Judge, User};
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

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('judges')->ignore($this->route('judge'))->where('contest_id', $contest->id)],
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'Selected Judge',
            'name' => 'Judge Name',
        ];
    }
}
