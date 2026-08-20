<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRmaRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi yang diterapkan untuk form RMA.
     */
    public function rules(): array
    {
        return [
            'nama_pemohon'      => 'required|string',
            'nama_manager'      => 'required|string',
            'is_material_rusak' => 'required|boolean',
            'ttd_pemohon'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'so_po'             => 'required|string',
            'valuation_type'    => 'required|in:ex-project,dismantle,rusak-L,rusak-TL',
            'tanggal'           => 'required|date',
            'lokasi_asal'       => 'required|string',
            'merk'              => 'required|string',
            'type'              => 'required|string',
            'material_number'   => 'nullable|string',
            'description'       => 'nullable|string',
            'kerusakan'         => 'nullable|array',
            'alasan'            => 'nullable|string',
            'serial_number'     => 'required|string',
            'foto_material'     => 'required|array',
            'foto_material.*'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}