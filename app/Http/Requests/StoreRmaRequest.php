<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator as LaravelValidator;

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
            'judul_rma'         => 'nullable|string|max:255',
            'nama_pemohon'      => 'required|string|max:255',
            'nama_manager'      => 'required|string|max:255',
            'is_material_rusak' => 'required|boolean',
            'ttd_pemohon'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'so_po'             => 'required|string|max:255',
            'valuation_type'    => 'required|in:ex-project,dismantle,rusak-L,rusak-TL',
            'tanggal'           => 'required|date',
            'lokasi_asal'       => 'required|string|max:255',
            'types' => 'required|array|min:1',
            'types.*.merk' => 'required|string|max:255',
            'types.*.type' => 'required|string|max:255',
            'types.*.material_number' => 'required|string|max:255',
            'types.*.serial_numbers' => 'required|array|min:1',
            'types.*.serial_numbers.*' => 'required|string|max:255',
            'description'       => 'required|string',
            'kerusakan'         => 'nullable|array',
            'alasan'            => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|array',
            'photos.*.*' => 'nullable|array',
            'photos.*.*.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function withValidator(LaravelValidator $validator): void
    {
        $validator->after(function (LaravelValidator $validator) {
            $hasPhoto = false;
            $photoFiles = $this->allFiles()['photos'] ?? [];
            array_walk_recursive($photoFiles, function ($file) use (&$hasPhoto) {
                if ($file) $hasPhoto = true;
            });
            if (!$hasPhoto) $validator->errors()->add('photos', 'Unggah minimal satu foto material pada grup SN yang sesuai.');
        });
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
            'types.*.merk' => 'Merk Perangkat',
            'types.*.type' => 'Tipe Perangkat',
            'types.*.serial_numbers.*' => 'Serial Number (SN)',
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
