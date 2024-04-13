<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContestRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'scoring_system' => [
                'nullable',
                Rule::requiredIf(! $this->route('contest')->categories()->whereHas('scores')->count()),
                Rule::in(array_keys(config('options.scoring_systems'))),
            ],
            'logo' => [
                'nullable',
                'file',
                'mimes:' . collect(config('options.image.mimes'))->implode(','),
                'mimetypes:' . collect(config('options.image.mime_types'))->implode(','),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'scoring_system' => 'Scoring System',
            'logo' => 'Logo',
        ];
    }
}
