<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|regex:/^[a-zA-Z0-9-_]+$/|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please specify a username.',
            'name.string' => 'The username may only contain letters, numbers, and dashes.',
            'name.regex' => 'The username may only contain letters, numbers, and dashes.',
            'name.max' => 'Please choose a shorter username.',
            'name.unique' => 'This username has already been taken.',
            'email.required' => 'Please specify a valid email address.',
            'email.string' => 'Please specify a valid email address.',
            'email.email' => 'Please specify a valid email address.',
            'email.max' => 'Please specify a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'Please choose a password for you account.',
            'password.string' => 'Please choose a password for you account.',
            'password.min' => 'Your password must be at least 6 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
