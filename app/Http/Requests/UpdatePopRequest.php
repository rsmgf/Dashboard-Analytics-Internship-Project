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
            'tipe_pop'       => 'nullable|string',
            'jenis_bangunan' => 'nullable|string',
        ];
    }
}