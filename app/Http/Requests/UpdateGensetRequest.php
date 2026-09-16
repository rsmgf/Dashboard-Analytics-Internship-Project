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
            'photo_genset'              => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'keterangan_gambar_genset'  => ['nullable', 'string', 'max:500'],
            'photo_engine'              => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'keterangan_gambar_engine'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'pic.required'           => 'PIC wajib diisi.',
            'bentuk_fisik.required'  => 'Bentuk Fisik wajib diisi.',
            'merk_genset.required'   => 'Merk Genset wajib dipilih.',
            'model.required'         => 'Model wajib dipilih.',
            'sn_genset.required'     => 'SN Genset wajib diisi.',
            'kapasitas_kva.required' => 'Kapasitas KVA wajib diisi.',
            'kapasitas_kva.numeric'  => 'Kapasitas KVA harus berupa angka.',
            'tipe_engine.required'   => 'Tipe Engine wajib dipilih.',
            'sn_engine.required'     => 'SN Engine wajib diisi.',
            'tahun_pasang.required'  => 'Tahun Pasang wajib dipilih.',
            'tahun_pasang.integer'   => 'Tahun Pasang harus berupa angka.',
            'tanggal_pm.date'        => 'Tanggal PM tidak valid.',
            'photo_genset.image'     => 'Foto Genset harus berupa gambar.',
            'photo_genset.mimes'     => 'Format foto Genset harus JPG, JPEG, atau PNG.',
            'photo_genset.max'       => 'Ukuran foto Genset maksimal 10 MB.',
            'photo_engine.image'     => 'Foto Engine harus berupa gambar.',
            'photo_engine.mimes'     => 'Format foto Engine harus JPG, JPEG, atau PNG.',
            'photo_engine.max'       => 'Ukuran foto Engine maksimal 10 MB.',
        ];
    }
}
