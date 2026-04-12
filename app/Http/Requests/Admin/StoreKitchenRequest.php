<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKitchenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'person_in_charge'    => ['required', 'string', 'max:255'],
            'address'             => ['required', 'string'],
            'phone'               => ['nullable', 'string', 'max:20'],
            'production_capacity' => ['required', 'integer', 'min:1'],
            'operational_status'  => ['required', 'in:active,inactive,maintenance'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                => 'Nama dapur wajib diisi.',
            'person_in_charge.required'    => 'Penanggung jawab wajib diisi.',
            'address.required'             => 'Alamat wajib diisi.',
            'production_capacity.required' => 'Kapasitas produksi wajib diisi.',
            'production_capacity.min'      => 'Kapasitas produksi minimal 1.',
            'operational_status.required'  => 'Status operasional wajib dipilih.',
        ];
    }
}
