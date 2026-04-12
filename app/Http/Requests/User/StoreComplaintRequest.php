<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isUser();
    }

    public function rules(): array
    {
        return [
            'category_id'   => ['required', 'exists:complaint_categories,id'],
            'title'         => ['required', 'string', 'min:10', 'max:255'],
            'description'   => ['required', 'string', 'min:20'],
            'priority'      => ['required', 'in:low,medium,high'],
            'attachments'   => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'   => 'Kategori aduan wajib dipilih.',
            'category_id.exists'     => 'Kategori tidak ditemukan.',
            'title.required'         => 'Judul aduan wajib diisi.',
            'title.min'              => 'Judul minimal 10 karakter.',
            'description.required'   => 'Deskripsi aduan wajib diisi.',
            'description.min'        => 'Deskripsi minimal 20 karakter.',
            'priority.required'      => 'Prioritas wajib dipilih.',
            'priority.in'            => 'Prioritas tidak valid.',
            'attachments.max'        => 'Maksimal 5 file lampiran.',
            'attachments.*.mimes'    => 'File harus berupa gambar (JPEG, PNG, WebP) atau PDF.',
            'attachments.*.max'      => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
