<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGensetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // General Info
            'pic'                       => ['required', 'string', 'max:255'],
            'bentuk_fisik'              => ['required', 'string', 'max:255'],

            // Checklist Genset
            'merk_genset'               => ['required', 'string', 'max:255'],
            'merk_genset_others'        => ['nullable', 'string', 'max:255'],
            'model'                     => ['required', 'string', 'max:255'],
            'model_others'              => ['nullable', 'string', 'max:255'],
            'sn_genset'                 => ['required', 'string', 'max:255'],
            'kapasitas_kva'             => ['required', 'numeric', 'min:0'],
            'tipe_engine'               => ['required', 'string', 'max:255'],
            'tipe_engine_others'        => ['nullable', 'string', 'max:255'],
            'sn_engine'                 => ['required', 'string', 'max:255'],

            // Uji Genset
            'tahun_pasang'              => ['required', 'integer', 'min:2013', 'max:2045'],
            'tanggal_pm'                => ['nullable', 'date'],

            // Foto (nullable saat update — jika tidak diupload, foto lama tetap dipakai)
            'photo_genset'              => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'keterangan_gambar_genset'  => ['nullable', 'string', 'max:500'],
            'photo_engine'              => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'keterangan_gambar_engine'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pic'                      => 'PIC',
            'bentuk_fisik'             => 'Bentuk Fisik Genset',
            'merk_genset'              => 'Merk Genset',
            'merk_genset_others'       => 'Merk Genset (Lainnya)',
            'model'                    => 'Model Genset',
            'model_others'             => 'Model Genset (Lainnya)',
            'sn_genset'                => 'Serial Number Genset',
            'kapasitas_kva'            => 'Kapasitas kVA',
            'tipe_engine'              => 'Tipe Engine',
            'tipe_engine_others'       => 'Tipe Engine (Lainnya)',
            'sn_engine'                => 'Serial Number Engine',
            'tahun_pasang'             => 'Tahun Pasang',
            'tanggal_pm'               => 'Tanggal PM Terakhir',
            'photo_genset'             => 'Foto Genset',
            'keterangan_gambar_genset' => 'Keterangan Foto Genset',
            'photo_engine'             => 'Foto Engine',
            'keterangan_gambar_engine' => 'Keterangan Foto Engine',
        ];
    }

    public function messages(): array
    {
        return [
            'pic.required'                 => 'Nama PIC wajib diisi.',
            'bentuk_fisik.required'        => 'Bentuk Fisik Genset wajib dipilih.',
            'merk_genset.required'         => 'Merk Genset wajib dipilih.',
            'model.required'               => 'Model Genset wajib dipilih.',
            'sn_genset.required'           => 'Serial Number Genset wajib diisi.',
            'kapasitas_kva.required'       => 'Kapasitas kVA wajib diisi.',
            'kapasitas_kva.numeric'        => 'Kapasitas kVA harus berupa angka.',
            'kapasitas_kva.min'            => 'Kapasitas kVA tidak boleh bernilai negatif.',
            'tipe_engine.required'         => 'Tipe Engine wajib dipilih.',
            'sn_engine.required'           => 'Serial Number Engine wajib diisi.',
            'tahun_pasang.required'        => 'Tahun Pasang wajib dipilih.',
            'tahun_pasang.integer'         => 'Tahun Pasang harus berupa angka bulat (contoh: 2024).',
            'tahun_pasang.min'             => 'Tahun Pasang tidak boleh kurang dari :min.',
            'tahun_pasang.max'             => 'Tahun Pasang tidak boleh lebih dari :max.',
            'tanggal_pm.date'              => 'Tanggal PM Terakhir harus berupa tanggal yang valid.',
            'photo_genset.image'           => 'Berkas Foto Genset harus berupa gambar.',
            'photo_genset.mimes'           => 'Format Foto Genset harus JPG, JPEG, atau PNG.',
            'photo_genset.max'             => 'Ukuran Foto Genset maksimal 2 MB.',
            'keterangan_gambar_genset.max' => 'Keterangan Foto Genset maksimal :max karakter.',
            'photo_engine.image'           => 'Berkas Foto Engine harus berupa gambar.',
            'photo_engine.mimes'           => 'Format Foto Engine harus JPG, JPEG, atau PNG.',
            'photo_engine.max'             => 'Ukuran Foto Engine maksimal 2 MB.',
            'keterangan_gambar_engine.max' => 'Keterangan Foto Engine maksimal :max karakter.',
        ];
    }
}
