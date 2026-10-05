<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID POP dari parameter URL
        $popId = $this->route('id');

        return [
            'kode_pop'       => 'required|string|unique:pops,kode_pop,' . $popId,
            'nama_pop'       => 'required|string',
            'provinsi'       => 'required|string',
            'kota_kabupaten' => 'required|string',
            'tipe_pop'       => 'nullable|in:POP-SB,POP-A,POP-B,POP-D',
            'jenis_bangunan' => 'nullable|in:Shelter,Shelter CKD,Shelter Permanen,Mini Shelter,ODC,Mini POP,Mikro POP,OLT Gantung',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_pop'       => 'Kode POP',
            'nama_pop'       => 'Nama POP',
            'provinsi'       => 'Provinsi',
            'kota_kabupaten' => 'Kota / Kabupaten',
            'tipe_pop'       => 'Tipe POP',
            'jenis_bangunan' => 'Jenis Bangunan',
            'latitude'       => 'Latitude',
            'longitude'      => 'Longitude',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_pop.required'       => 'Kode POP wajib diisi.',
            'kode_pop.unique'         => 'Kode POP sudah terdaftar di sistem. Mohon gunakan kode yang berbeda.',
            'nama_pop.required'       => 'Nama POP wajib diisi.',
            'provinsi.required'       => 'Provinsi wajib dipilih.',
            'kota_kabupaten.required' => 'Kota/Kabupaten wajib dipilih.',
            'tipe_pop.in'             => 'Pilihan Tipe POP tidak valid. Harus salah satu dari: POP-SB, POP-A, POP-B, POP-D.',
            'jenis_bangunan.in'       => 'Pilihan Jenis Bangunan tidak valid.',
            'latitude.numeric'        => 'Latitude harus berupa angka/desimal.',
            'latitude.between'        => 'Nilai Latitude harus antara -90 sampai 90.',
            'longitude.numeric'       => 'Longitude harus berupa angka/desimal.',
            'longitude.between'       => 'Nilai Longitude harus antara -180 sampai 180.',
        ];
    }
}