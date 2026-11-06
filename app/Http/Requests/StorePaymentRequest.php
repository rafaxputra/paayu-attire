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
            // Previously only payment_proof / payment_method / confirm_payment
            // were validated, but checkout_store() also trusts these hidden
            // inputs — a tampered request could submit duration 0 or a
            // negative value (negative total), a non-existent product slug,
            // a size that does not exist, or an ended_at before started_at.
            'product_slug' => ['required', 'string', 'exists:products,slug'],
            'selected_size' => ['required', 'string', 'max:10'],
            'duration' => ['required', 'integer', 'min:1', 'max:365'],
            // started_at is intentionally NOT required to be in the future:
            // the PAYMENT_FAILED re-upload form resubmits the original
            // (possibly past) booking dates.
            'started_at' => ['required', 'date'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,png'],
            'payment_method' => ['required', 'string', 'in:BCA,BRI'],
            'confirm_payment' => ['accepted'],
        ];
    }
}
