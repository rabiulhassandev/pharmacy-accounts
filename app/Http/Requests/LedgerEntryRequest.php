<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LedgerEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entry_date' => ['sometimes', 'required', 'date'],
            'medicine_purchase_company' => ['nullable', 'numeric', 'min:0'],
            'medicine_purchase_shop' => ['nullable', 'numeric', 'min:0'],
            'medicine_purchase_other' => ['nullable', 'numeric', 'min:0'],
            'payment_company' => ['nullable', 'numeric', 'min:0'],
            'payment_shop' => ['nullable', 'numeric', 'min:0'],
            'payment_other' => ['nullable', 'numeric', 'min:0'],
            'daily_sale' => ['nullable', 'numeric', 'min:0'],
            'hole_sale' => ['nullable', 'numeric', 'min:0'],
            'other_sale' => ['nullable', 'numeric', 'min:0'],
            'due_purchase' => ['nullable', 'numeric', 'min:0'],
            'due_sale' => ['nullable', 'numeric', 'min:0'],
            'daily_staff_cost' => ['nullable', 'numeric', 'min:0'],
            'other_cost' => ['nullable', 'numeric', 'min:0'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'bill' => ['nullable', 'numeric', 'min:0'],
            'rent' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
