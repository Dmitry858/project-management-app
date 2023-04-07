<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'max:255'],
            'last_name' => ['max:255'],
            'email' => ['required', 'unique:users,email,'.$this->route('user'), 'max:255']
        ];

        $data = $this->validationData();

        if ($data['password'])
        {
            $rules['password'] = ['required', 'confirmed', 'max:255', Rules\Password::defaults()];
        }

        if (isset($data['photo']))
        {
            $rules['photo'] = ['mimes:jpeg,jpg,png,gif,bmp,webp'];
        }

        return $rules;
    }
}
