<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcRequest extends FormRequest
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
            'jenis_freon_others'    => ['nullable', 'string', 'max:255'],
            'merk_ac'               => ['required', 'string', 'max:255'],
            'merk_ac_others'        => ['nullable', 'string', 'max:255'],
            'tahun_manufaktur'      => ['required', 'integer', 'min:2013', 'max:2045'],
            'type_ac'               => ['required', 'string', 'max:255'],
            'type_ac_others'        => ['nullable', 'string', 'max:255'],
            'pk'                    => ['required', 'string', 'max:10'],
            'tanggal_instalasi'     => ['nullable', 'date'],
            'tanggal_terakhir_pm'   => ['nullable', 'date'],

            // Foto (nullable saat update — jika tidak diupload, foto lama tetap dipakai)
            'photo_ac'              => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'keterangan_gambar_ac'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_freon.required'      => 'Jenis Freon wajib dipilih.',
            'merk_ac.required'          => 'Merk AC wajib dipilih.',
            'tahun_manufaktur.required' => 'Tahun Manufaktur wajib dipilih.',
            'tahun_manufaktur.integer'  => 'Tahun Manufaktur harus berupa angka.',
            'type_ac.required'          => 'Type AC wajib dipilih.',
            'pk.required'               => 'PK wajib dipilih.',
            'photo_ac.image'            => 'Foto AC harus berupa gambar.',
            'photo_ac.mimes'            => 'Format foto AC harus JPG, JPEG, atau PNG.',
            'photo_ac.max'              => 'Ukuran foto AC maksimal 10 MB.',
        ];
    }
}
