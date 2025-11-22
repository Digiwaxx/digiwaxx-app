<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Login Request Validation
 *
 * Validates and sanitizes login form data
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Everyone can attempt to login
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'max:255',
                // Accept either email or username
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && strlen($value) < 3) {
                        $fail('The email must be a valid email address or username with at least 3 characters.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:1', // Allow any password length for legacy accounts
                'max:255',
            ],
            'membertype' => [
                'required',
                'string',
                'in:client,member', // Only allow these two types
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email or username.',
            'email.max' => 'Email/username is too long.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Please enter your password.',
            'password.max' => 'Password is too long.',
            'membertype.required' => 'Please select account type.',
            'membertype.in' => 'Invalid account type selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize input before validation
        $this->merge([
            'email' => trim($this->email ?? ''),
            'password' => $this->password ?? '',
            'membertype' => strtolower(trim($this->membertype ?? '')),
        ]);
    }
}
