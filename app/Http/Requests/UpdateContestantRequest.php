<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContestantRequest extends FormRequest
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

        $contestant = $contest->contestants()->findOrFail($this->route('contestant'));

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('contestants')->ignore($contestant)->where('contest_id', $contest->id)],
            'description' => ['required', 'string', 'max:255'],
            'number' => ['required', 'integer', 'min:1', 'max:255', Rule::unique('contestants')->ignore($contestant)->where('contest_id', $contest->id)],
            'picture' => ['nullable', 'file', 'image'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Full Name',
            'description' => 'Description',
            'number' => 'Number',
            'picture' => 'Profile Picture',
        ];
    }
}
