<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PinUpdateRequest extends FormRequest
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
        return [
            'pin' => 'required|digits:6',
            'new_pin' => 'required|digits:6|different:pin',
            'confirm_new_pin' => 'required|digits:6|same:new_pin',
        ];
    }
}
