<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'duration' => ['required', 'integer', 'min:1'],
            'started_at' => ['required', 'date', 'after_or_equal:today'],
            'selected_size' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
        ];
    }
}
