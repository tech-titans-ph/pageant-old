<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SetScoreRequest extends FormRequest
{
    protected $length;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('judge')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $category = $this->route('category');

        $maxPointsPercentage = 1;

        if ($category->has_criterias) {
            $group = $category->criterias()->find(request()->input('group_id') ?? 0);
        } else {
            $group = $category;
        }

        if ($group) {
            $maxPointsPercentage = $group->max_points_percentage;
        }

        $numberSegments = explode('.', (float) $group->step);

        $decimal = (int) ($numberSegments[1] ?? 0);

        $this->length = $decimal ? strlen($decimal) : 0;

        $regex = $this->length ? Str::replaceFirst(':decimal', $this->length, 'regex:/^\d+(\.\d{0,:decimal})?$/') : null;

        $min = $this->length ? ('0.' . str_pad(1, $this->length, '0', STR_PAD_LEFT)) : 1;

        return [
            'group_id' => [
                'required',
                Rule::exists($group->getTable(), 'id')->where(function ($query) use ($category) {
                    $query->when($category->has_criterias, function ($query) use ($category) {
                        return $query->where('category_id', $category->id);
                    });
                }),
            ],
            'points' => [
                Rule::requiredIf(function () use ($group) {
                    return $group;
                }),
                'numeric',
                $regex,
                // 'min:' . $min,
                'min:0',
                'max:' . $maxPointsPercentage,
            ],
        ];
    }

    public function attributes()
    {
        return [
            'group_id' => $this->route('category')->has_criterias ? 'Criteria' : 'Category',
            'points' => 'Points',
        ];
    }

    public function messages()
    {
        return [
            'points.regex' => "Points must not exceed {$this->length} decimal places.",
        ];
    }
}
