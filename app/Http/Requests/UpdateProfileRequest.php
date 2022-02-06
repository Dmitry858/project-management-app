<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'max:255']
        ];

        $data = $this->validationData();

        if ($data['email'] !== Auth::user()->email)
        {
            $rules['email'] = ['required', 'unique:users', 'max:255'];
        }

        if ($data['password'])
        {
            $rules['password'] = ['required', 'confirmed', 'max:255', Rules\Password::defaults()];
        }

        return $rules;
    }
}
