<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $sanitize = static fn (?string $value): ?string => $value === null
            ? null
            : trim(strip_tags($value));

        $this->merge([
            'first_name' => $sanitize($this->input('first_name')),
            'last_name' => $sanitize($this->input('last_name')),
            'email' => $sanitize($this->input('email')),
            'phone' => $sanitize($this->input('phone')),
            'address' => $sanitize($this->input('address')),
            'city' => $sanitize($this->input('city')),
            'state' => $sanitize($this->input('state')),
            'postal_code' => $sanitize($this->input('postal_code')),
            'country' => $sanitize($this->input('country')),
            'notes' => $sanitize($this->input('notes')),
        ]);
    }
}
