<?php

namespace Modules\TitikDrainase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TitikDrainaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'petugas';
    }

    public function rules(): array
    {
        return [
            'nama_titik' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'text', 'max:80'],
            'kedalaman_cm' => ['required', 'integer', 'max:80'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'kondisi.in' => 'Kondisi titikdrainase tidak valid.',
        ];
    }
}