<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $profile = $this->route('customer') ?? $this->route('customer_profile');
        $userId = $profile?->user_id;

        return [
            'customer_type' => ['required', Rule::in(['individual', 'company'])],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'rut' => ['nullable', 'string', 'max:30'],
            'business_activity' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'preferred_price_list_id' => ['nullable', 'exists:price_lists,id'],
            'is_active' => ['nullable', 'boolean'],
            'newsletter' => ['nullable', 'boolean'],
            'accept_promotions' => ['nullable', 'boolean'],
            'accept_sms' => ['nullable', 'boolean'],
            'accept_whatsapp' => ['nullable', 'boolean'],
            'accept_email_marketing' => ['nullable', 'boolean'],
            'accept_cookies' => ['nullable', 'boolean'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:customer_tags,id'],
            'password' => [$this->isMethod('post') ? 'nullable' : 'sometimes', 'nullable', 'confirmed', 'min:8'],
        ];
    }
}
