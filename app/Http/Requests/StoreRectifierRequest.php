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
            // Informasi dasar
            'nama_alias'              => 'required|string|max:255',
            'deskripsi'               => 'nullable|string',
            'tanggal_pemeriksaan'     => 'nullable|date',
            'pic'                     => 'nullable|string|max:255',

            // Data teknis utama
            'merk'                    => 'required|string|max:255',
            'type'                    => 'required|string|max:255',
            'sn_rectifier'            => 'required|string|unique:rectifiers,sn_rectifier',
            'kapasitas_slot'          => 'required|integer|min:1',

            // Data teknis tambahan (semuanya opsional)
            'couple'                  => 'nullable|string|max:255',
            'type_modul_controller'   => 'nullable|string|max:255',
            'type_modul_power'        => 'nullable|string|max:255',
            'kapasitas_rectifier'     => 'nullable|string|max:255',
            'beban'                   => 'nullable|string|max:255',
            'utilisasi'               => 'nullable|string|max:255',
            'foto_rectifier'          => 'nullable|image|mimes:jpeg,png,jpg|max:10240',

            // Modules
            'modules'                    => 'nullable|array',
            'modules.*.sn_modul'         => 'nullable|string|unique:rectifier_modules,sn_modul',
            'modules.*.kapasitas_ampere' => 'nullable|string',

            // Outputs MCB
            'outputs'                    => 'nullable|array',
            'outputs.*.nama_mcb'         => 'required|string',
            'outputs.*.merk_mcb'         => 'nullable|string',
            'outputs.*.kapasitas_mcb'    => 'nullable|string',
            'outputs.*.peruntukan'       => 'nullable|string',
        ];
    }
}