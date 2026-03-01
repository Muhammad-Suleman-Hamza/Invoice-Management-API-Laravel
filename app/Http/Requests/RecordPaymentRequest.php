<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\PaymentMethod;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => [
                'required',
                Rule::in(array_column(PaymentMethod::cases(), 'value'))
            ],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ];
    }
}
