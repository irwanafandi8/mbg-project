<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isUser();
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Pesan saran wajib diisi.',
            'message.min' => 'Saran minimal 10 karakter.',
            'message.max' => 'Saran maksimal 2000 karakter.',
        ];
    }
}
