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

    public function messages(): array
    {
        return [
            'tipe_pop.in'       => 'Tipe POP harus salah satu dari: POP-SB, POP-A, POP-B, POP-D.',
            'jenis_bangunan.in' => 'Building harus salah satu dari: Shelter, Shelter CKD, Shelter Permanen, Mini Shelter, ODC, Mini POP, Mikro POP, OLT Gantung.',
        ];
    }
}