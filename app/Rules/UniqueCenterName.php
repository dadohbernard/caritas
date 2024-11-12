<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Center;

class UniqueCenterName implements Rule
{
    public function passes($attribute, $value)
    {


        // Ensure the center name is unique for records with is_deleted as 0
        return !Center::where('center_name', $value)->where('is_deleted', 0)->exists();
        }


    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}

