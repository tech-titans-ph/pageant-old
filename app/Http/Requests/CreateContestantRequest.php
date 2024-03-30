<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateContestantRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('contestants')->where('contest_id', $contest->id)],
            'alias' => ['required', 'string', 'max:255'],
            'avatar' => [
                'required',
                'file',
                'mimes:' . collect(config('options.image.mimes'))->implode(','),
                'mimetypes:' . collect(config('options.image.mime_types'))->implode(','),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Full Name',
            'alias' => 'Alias',
            'avatar' => 'Profile Picture',
        ];
    }
}
