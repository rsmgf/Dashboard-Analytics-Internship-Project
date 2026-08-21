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
            // Informasi dasar
            'nama_alias'              => 'required|string|max:255',
            'deskripsi'               => 'nullable|string',
            'tanggal_pemeriksaan'     => 'nullable|date',
            'pic'                     => 'nullable|string|max:255',

            // Data teknis utama
            'merk'                    => 'required|string|max:255',
            'type'                    => 'required|string|max:255',
            'sn_rectifier'            => 'required|string|unique:rectifiers,sn_rectifier,' . $rectifierId,
            'kapasitas_slot'          => 'required|integer|min:1',

            // Data teknis tambahan
            'couple'                  => 'nullable|string|max:255',
            'type_modul_controller'   => 'nullable|string|max:255',
            'type_modul_power'        => 'nullable|string|max:255',
            'kapasitas_rectifier'     => 'nullable|string|max:255',
            'beban'                   => 'nullable|string|max:255',
            'utilisasi'               => 'nullable|string|max:255',
            'foto_rectifier'          => 'nullable|image|mimes:jpeg,png,jpg|max:10240',

            // Modules
            'modules'                    => 'nullable|array',
            'modules.*.id'               => 'nullable|integer',
            'modules.*.sn_modul'         => 'nullable|string',
            'modules.*.kapasitas_ampere' => 'nullable|string',

            // Outputs
            'outputs'                    => 'nullable|array',
            'outputs.*.id'               => 'nullable|integer',
            'outputs.*.nama_mcb'         => 'required|string',
            'outputs.*.merk_mcb'         => 'nullable|string',
            'outputs.*.kapasitas_mcb'    => 'nullable|string',
            'outputs.*.peruntukan'       => 'nullable|string',
        ];
    }
}