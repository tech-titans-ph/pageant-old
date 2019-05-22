<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\ContestCategory;

class uniqueContestCategory implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ContestCategory::where($attribute, $value)
            ->whereContestId(session('activeContest')->id)
            ->first() ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already taken.';
    }
}
