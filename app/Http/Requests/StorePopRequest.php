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
            'tipe_pop'       => 'nullable|string',
            'jenis_bangunan' => 'nullable|string',
            'lokasi'         => 'nullable|string',
        ];
    }
}