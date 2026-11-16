<?php

namespace Modules\Laporan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $laporan = $this->route('laporan');

        if (!$user || $user->role !== 'warga') {
            return false;
        }

        // Saat membuat pengaduan, belum ada objek pengaduan.
        if (!$laporan) {
            return true;
        }

        // Saat update, harus milik sendiri dan masih Baru.
        return (int) $laporan->user_id === (int) $user->id
            && $laporan->status === 'Baru';
    }

    public function rules(): array
    {
        return [
            'status_kondisi' => [
                'required',
                'integer',
                'exists:fasilitas,id',
            ],
            'deskripsi' => [
                'required',
                'string',
                'max:2000',
            ],
            'foto_bukti' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'fasilitas_id.exists' => 'TitikDrainase tidak ditemukan.',
            'deskripsi.max' => 'Deskripsi maksimal 2000 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Gunakan JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}