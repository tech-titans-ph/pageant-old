<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'scoring_system' => ['required', Rule::in(array_keys(config('options.scoring_systems')))],
            'logo' => [
                'required',
                'file',
                'mimes:' . collect(config('options.image.mimes'))->implode(','),
                'mimetypes:' . collect(config('options.image.mime_types'))->implode(','),
            ],
            'contestant_count' => ['required', 'integer', 'min:2', 'max:' . $contest->contestants()->count()],
            'include_judges' => ['boolean'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'scoring_system' => 'Scoring System',
            'logo' => 'Logo',
            'contestant_count' => 'Number of Contestants',
        ];
    }
}
