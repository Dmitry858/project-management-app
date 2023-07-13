<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $data = $this->validationData();

        $rules = [
            'title' => isset($data['ajax']) ? ['max:255'] : ['required', 'max:255'],
            'start' => isset($data['ajax']) ? ['date'] : ['required', 'date'],
            'end' => isset($data['ajax']) ? ['date'] : ['required', 'date'],
            'is_allday' => ['integer'],
            'is_private' => ['integer'],
        ];

        return $rules;
    }
}
