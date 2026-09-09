<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatteryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // General Information
            'building'                 => ['required', 'string', 'max:255'],
            'pic'                      => ['required', 'string', 'max:255'],
            'type_pop'                 => ['required', 'string', 'max:255'],

            // Checklist Baterai
            'rectifier_id'             => ['required', 'integer', 'min:1'],
            'nomor_bank'               => ['required', 'string', 'max:255'],
            'merk_battery'             => ['required', 'string', 'max:255'],
            'tipe_battery'             => ['required', 'string', 'max:255'],
            'jenis_battery'            => ['required', 'in:Lithium,VRLA'],
            'kapasitas_battery'        => ['required', 'numeric', 'in:20,50,100,200'],
            'kapasitas_uji'            => ['nullable', 'string', 'max:20'],
            'kapasitas_battery_persen' => ['nullable', 'numeric', 'min:0'],
            'performa_baterai'         => ['nullable', 'string', 'max:255'],

            // Uji Baterai
            'tanggal_uji_terakhir'     => ['nullable', 'date'],
            'tanggal_penggantian'      => ['nullable', 'date'],
            'status_uji'               => ['nullable', 'string', 'max:255'],
            'area_sti'                 => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'building.required'          => 'Building wajib diisi.',
            'pic.required'               => 'PIC wajib diisi.',
            'type_pop.required'          => 'Type POP wajib diisi.',
            'rectifier_id.required'      => 'Nomor Rectifier wajib dipilih.',
            'rectifier_id.integer'       => 'Rectifier tidak valid.',
            'nomor_bank.required'        => 'Nomor Bank Baterai wajib diisi.',
            'merk_battery.required'      => 'Merk Baterai wajib dipilih.',
            'tipe_battery.required'      => 'Tipe Baterai wajib dipilih.',
            'jenis_battery.required'     => 'Jenis Baterai wajib dipilih.',
            'jenis_battery.in'           => 'Jenis Baterai harus Lithium atau VRLA.',
            'kapasitas_battery.required' => 'Kapasitas Baterai wajib dipilih.',
            'kapasitas_battery.in'       => 'Pilihan Kapasitas Baterai adalah 20, 50, 100, atau 200 AH.',
            'area_sti.required'          => 'Area STI wajib diisi.',
        ];
    }
}
