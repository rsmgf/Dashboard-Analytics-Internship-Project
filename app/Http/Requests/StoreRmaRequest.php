<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRmaRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Tangani kegagalan validasi agar mengembalikan respons JSON 422 jika request via AJAX.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Data yang dikirimkan belum lengkap atau tidak valid.',
                'errors'  => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }

    /**
     * Aturan validasi yang diterapkan untuk form RMA.
     */
    public function rules(): array
    {
        return [
            'nama_pemohon'      => 'required|string|max:255',
            'nama_manager'      => 'required|string|max:255',
            'is_material_rusak' => 'required|boolean',
            'ttd_pemohon'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'so_po'             => 'required|string|max:255',
            'valuation_type'    => 'required|in:ex-project,dismantle,rusak-L,rusak-TL',
            'tanggal'           => 'required|date',
            'lokasi_asal'       => 'required|string|max:255',
            'merk'              => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'material_number'   => 'nullable|string|max:255',
            'description'       => 'required|string',
            'kerusakan'         => 'nullable|array',
            'alasan'            => 'nullable|string',
            'serial_number'     => 'required|string|max:255',
            'foto_material'     => 'required|array|min:1',
            'foto_material.*'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Nama atribut yang ramah pengguna dalam Bahasa Indonesia.
     */
    public function attributes(): array
    {
        return [
            'so_po'           => 'No. Dokumen (SO/PO/IO)',
            'valuation_type'  => 'Valuation Type',
            'tanggal'         => 'Tanggal',
            'lokasi_asal'     => 'Lokasi Asal',
            'nama_manager'    => 'Nama Supervisor / Manager',
            'merk'            => 'Merk Perangkat',
            'type'            => 'Tipe Perangkat',
            'serial_number'   => 'Serial Number (SN)',
            'description'     => 'Description (Deskripsi Kondisi)',
            'nama_pemohon'    => 'Nama Pemohon / Engineer',
            'ttd_pemohon'     => 'Foto Tanda Tangan',
            'foto_material'   => 'Foto Material Utama',
            'foto_material.*' => 'Foto Material',
        ];
    }

    /**
     * Pesan kustom validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'required'             => ':attribute wajib diisi.',
            'image'                => ':attribute harus berupa file gambar.',
            'mimes'                => ':attribute harus berformat JPG, JPEG, PNG, atau WEBP.',
            'max'                  => [
                'file'   => 'Ukuran :attribute maksimal :max KB (2 MB).',
                'string' => ':attribute maksimal :max karakter.',
            ],
            'foto_material.required' => 'Foto material utama wajib diunggah.',
            'foto_material.*.image'  => 'Setiap foto material harus berupa file gambar (JPG/PNG/WEBP).',
            'foto_material.*.max'    => 'Ukuran setiap foto material maksimal 2MB.',
            'ttd_pemohon.required'   => 'Foto tanda tangan pemohon wajib diunggah.',
            'ttd_pemohon.image'      => 'Tanda tangan harus berupa file gambar (JPG/PNG/WEBP).',
            'ttd_pemohon.max'        => 'Ukuran foto tanda tangan maksimal 2MB.',
            'valuation_type.required'=> 'Silakan pilih salah satu Valuation Type.',
            'valuation_type.in'      => 'Pilihan Valuation Type tidak valid.',
        ];
    }
}