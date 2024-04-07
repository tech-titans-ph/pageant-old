<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateBestinRequest extends FormRequest
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
        $table = [
            'category' => 'categories',
            'criteria' => 'criterias',
        ][$this->type] ?? '';

        return [
            'type' => ['required', 'in:category,criteria'],
            'type_id' => ['required', $table ? "exists:{$table},id" : null],
            'name' => ['required', 'string', 'max:191'],
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'Type',
            'type_id' => Str::title($this->type),
        ];
    }
}
