<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'npsn' => ['required', 'string', 'max:20', 'unique:schools,npsn'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'kitchen_id' => ['nullable', 'exists:kitchens,id'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama sekolah wajib diisi.',
            'npsn.required' => 'NPSN wajib diisi.',
            'npsn.unique' => 'NPSN sudah terdaftar.',
            'address.required' => 'Alamat wajib diisi.',
        ];
    }
}
