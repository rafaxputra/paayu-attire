<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'transaction_id' => ['required', 'exists:rental_transactions,trx_id'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,png'],
            'payment_method' => ['required', 'string', 'in:BCA,BRI'],
            'confirm_payment' => ['accepted'],
        ];
    }
}
