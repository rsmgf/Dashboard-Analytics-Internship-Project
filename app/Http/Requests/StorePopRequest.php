<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'kode_pop'       => 'required|string|unique:pops,kode_pop',
            'nama_pop'       => 'required|string',
            'provinsi'       => 'required|string',
            'kota_kabupaten' => 'required|string',
            'tipe_pop'       => 'nullable|in:POP-SB,POP-A,POP-B,POP-D',
            'jenis_bangunan' => 'nullable|in:Shelter,Shelter CKD,Shelter Permanen,Mini Shelter,ODC,Mini POP,Mikro POP,OLT Gantung',
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
        ];
    }
}