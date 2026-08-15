<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $complaint = $this->route('complaint');

        return auth()->check() &&
            auth()->user()->isUser() &&
            $complaint->user_id === auth()->id() &&
            $complaint->status->value === 'pending';
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:complaint_categories,id'],
            'title' => ['required', 'string', 'min:10', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori aduan wajib dipilih.',
            'title.required' => 'Judul aduan wajib diisi.',
            'title.min' => 'Judul minimal 10 karakter.',
            'description.required' => 'Deskripsi aduan wajib diisi.',
            'description.min' => 'Deskripsi minimal 20 karakter.',
        ];
    }
}
