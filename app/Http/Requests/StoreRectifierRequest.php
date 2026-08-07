<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRectifierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_alias'                 => 'required|string',
            'deskripsi'                  => 'nullable|string',
            'merk'                       => 'required|string',
            'type'                       => 'required|string',
            'sn_rectifier'               => 'required|string|unique:rectifiers,sn_rectifier',
            'kapasitas_slot'             => 'required|integer|min:1',
            
            'modules'                    => 'nullable|array',
            'modules.*.sn_modul'         => 'required|string|unique:rectifier_modules,sn_modul',
            'modules.*.kapasitas_ampere' => 'required|string',

            'outputs'                    => 'nullable|array',
            'outputs.*.merk_mcb'         => 'required|string',
            'outputs.*.kapasitas_mcb'    => 'required|integer',
            'outputs.*.peruntukan'       => 'required|string',
        ];
    }
}