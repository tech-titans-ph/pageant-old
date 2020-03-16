<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContestFromScoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'file', 'image'],
            'contestant_count' => ['required', 'integer', 'min:1', 'max:' . $contest->contestants()->count()],
            'include_judges' => ['nullable'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'logo' => 'Logo',
            'contestant_count' => 'Number of Contestants',
        ];
    }
}
