<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateJudgeRequest extends FormRequest
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
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Judge Name',
        ];
    }
}
