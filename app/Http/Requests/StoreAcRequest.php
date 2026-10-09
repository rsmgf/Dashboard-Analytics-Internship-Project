<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Detail AC
            'jenis_freon'           => ['required', 'string', 'max:255'],
            'pic'                   => ['required', 'string', 'max:255'],
            'jenis_freon_others'    => ['nullable', 'string', 'max:255'],
            'merk_ac'               => ['required', 'string', 'max:255'],
            'merk_ac_others'        => ['nullable', 'string', 'max:255'],
            'tahun_manufaktur'      => ['required', 'integer', 'min:2013', 'max:2045'],
            'type_ac'               => ['required', 'string', 'max:255'],
            'type_ac_others'        => ['nullable', 'string', 'max:255'],
            'pk'                    => ['required', 'string', 'max:10'],
            'tanggal_instalasi'     => ['nullable', 'date'],
            'tanggal_terakhir_pm'   => ['nullable', 'date'],
            'tanggal_pemeriksaan'   => ['nullable', 'date'],

            // Foto
            'photo_ac'              => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'keterangan_gambar_ac'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_freon'          => 'Jenis Freon',
            'jenis_freon_others'   => 'Jenis Freon (Lainnya)',
            'merk_ac'              => 'Merk AC',
            'merk_ac_others'       => 'Merk AC (Lainnya)',
            'tahun_manufaktur'     => 'Tahun Manufaktur',
            'type_ac'              => 'Tipe AC',
            'type_ac_others'       => 'Tipe AC (Lainnya)',
            'pk'                   => 'Kapasitas PK',
            'tanggal_instalasi'    => 'Tanggal Instalasi',
            'tanggal_terakhir_pm'  => 'Tanggal Terakhir PM',
            'photo_ac'             => 'Foto AC',
            'keterangan_gambar_ac' => 'Keterangan Foto AC',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_freon.required'      => 'Jenis Freon wajib dipilih.',
            'merk_ac.required'          => 'Merk AC wajib dipilih.',
            'tahun_manufaktur.required' => 'Tahun Manufaktur wajib dipilih.',
            'tahun_manufaktur.integer'  => 'Tahun Manufaktur harus berupa angka bulat (contoh: 2024).',
            'tahun_manufaktur.min'      => 'Tahun Manufaktur tidak boleh kurang dari :min.',
            'tahun_manufaktur.max'      => 'Tahun Manufaktur tidak boleh lebih dari :max.',
            'type_ac.required'          => 'Tipe AC wajib dipilih.',
            'pk.required'               => 'Kapasitas PK wajib dipilih.',
            'tanggal_instalasi.date'    => 'Tanggal Instalasi harus berupa tanggal yang valid.',
            'tanggal_terakhir_pm.date'  => 'Tanggal Terakhir PM harus berupa tanggal yang valid.',
            'photo_ac.image'            => 'Foto AC harus berupa berkas gambar.',
            'photo_ac.mimes'            => 'Format Foto AC harus JPG, JPEG, atau PNG.',
            'photo_ac.max'              => 'Ukuran Foto AC maksimal 2 MB.',
            'keterangan_gambar_ac.max'  => 'Keterangan Foto AC maksimal :max karakter.',
        ];
    }
}
