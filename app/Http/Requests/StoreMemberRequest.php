<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =[
            'first_name' => 'required',
            'last_name' => 'required',
            'cat_id' => 'required',
            'phone' => 'required|unique:members,phone',
            'address' => 'required',
            'dob' => 'required',
            'description' => 'required',
            'hospital' => 'required_if:cat_id,1',
            'school_name' => 'required_if:cat_id,2',
            'sdms_code' => 'required_if:cat_id,2',
            'other_support' => 'nullable'
        ];
 if ($this->input('cat_id') == 1) {
            $rules['hospital'] = 'required|string';
        } elseif ($this->input('cat_id') == 2) {
            $rules['school_name'] = 'required|string';
            $rules['sdms_code'] = 'required|string';
        }

        return $rules;
    }
}
