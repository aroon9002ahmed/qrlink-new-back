<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\Page;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'phone:EG', 'string', 'max:16', 'starts_with:+', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'page' => ['required', 'string', Rule::unique(Page::class, 'slug'), 'alpha_dash'], // ensure unique slug in pages.slug and basic slug format
        ];
    }

    public function messages(): array
    {
        return [


            'name.required' => __('validation.required', ['attribute' => 'Name']),
            'phone.required' => __('validation.required', ['attribute' => 'Phone Number']),
            'email.required' => __('validation.required', ['attribute' => 'Email Address']),
            'password.required' => __('validation.required', ['attribute' => 'Password']),
            'password.confirmed' => 'Password confirmation does not match',
            'email.unique' => __('validation.unique', ['attribute' => 'Email Address']),
            'page.required' => __('validation.required', ['attribute' => 'Page']),
            'phone.unique' => __('validation.unique', ['attribute' => 'Phone Number']),
            'phone.phone' => 'The :attribute must be a valid Egyptian phone number',
        ];
    }
}
