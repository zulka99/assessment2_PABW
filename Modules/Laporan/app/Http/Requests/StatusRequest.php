<?php

namespace Modules\Laporan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'petugas';
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['Baru', 'Diproses', 'Selesai']),
            ],
            'catatan' => [
                'nullable',
                'required_if:status,Selesai',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'catatan.required_if' =>
                'Isi catatan penanganan sebelum memilih Selesai.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}