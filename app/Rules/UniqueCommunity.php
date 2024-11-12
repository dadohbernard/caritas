<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Community;

class UniqueCommunity implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function passes($attribute, $value)
    {


        // Ensure the center name is unique for records with is_deleted as 0
        return !Community::where('community_name', $value)->where('is_deleted', 0)->exists();

    }

    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
