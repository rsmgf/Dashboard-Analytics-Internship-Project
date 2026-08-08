<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRectifierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID dari URL (misal: /pops/{pop}/rectifiers/{id})
        $rectifierId = $this->route('id');

        return [
            'nama_alias'                 => 'required|string',
            'deskripsi'                  => 'nullable|string',
            'merk'                       => 'required|string',
            'type'                       => 'required|string',
            // Kecualikan pengecekan unique untuk data ini sendiri
            'sn_rectifier'               => 'required|string|unique:rectifiers,sn_rectifier,' . $rectifierId,
            'kapasitas_slot'             => 'required|integer|min:1',
            
            'modules'                    => 'nullable|array',
            'modules.*.id'               => 'nullable|integer',
            'modules.*.sn_modul'         => 'required|string',
            'modules.*.kapasitas_ampere' => 'required|string',

            'outputs'                    => 'nullable|array',
            'outputs.*.id'               => 'nullable|integer',
            'outputs.*.merk_mcb'         => 'required|string',
            'outputs.*.kapasitas_mcb'    => 'required|integer',
            'outputs.*.peruntukan'       => 'required|string',
        ];
    }
}