<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatteryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('kapasitas_battery_persen') && is_string($this->kapasitas_battery_persen)) {
            $clean = trim(str_replace(['%', ' '], '', $this->kapasitas_battery_persen));
            $this->merge([
                'kapasitas_battery_persen' => $clean === '' ? null : $clean,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            // General Information
            'pic'                      => ['required', 'string', 'max:255'],

            // Checklist Baterai
            'rectifier_id'             => ['required', 'integer', 'min:1'],
            'nomor_bank'               => ['required', 'string', 'max:255'],
            'merk_battery'             => ['required', 'string', 'max:255'],
            'tipe_battery'             => ['required', 'string', 'max:255'],
            'jenis_battery'            => ['required', 'in:Lithium,VRLA'],
            'kapasitas_battery'        => ['required', 'numeric', 'min:1'],
            'kapasitas_uji'            => ['nullable', 'string', 'max:20'],
            'vrla_1'                   => ['nullable', 'string', 'max:20'],
            'vrla_2'                   => ['nullable', 'string', 'max:20'],
            'vrla_3'                   => ['nullable', 'string', 'max:20'],
            'vrla_4'                   => ['nullable', 'string', 'max:20'],
            'kapasitas_battery_persen' => ['nullable', 'numeric', 'min:0'],
            'performa_baterai'         => ['nullable', 'string', 'max:255'],
            'tegangan'                 => ['nullable', 'numeric', 'min:0'],
            'photo_battery'            => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'keterangan_gambar'        => ['nullable', 'string', 'max:255'],

            // Uji Baterai
            'tanggal_uji_terakhir'     => ['nullable', 'date'],
            'tanggal_penggantian'      => ['nullable', 'date'],
            'status_uji'               => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'pic.required'               => 'PIC wajib diisi.',
            'rectifier_id.required'      => 'Nomor Rectifier wajib dipilih.',
            'rectifier_id.integer'       => 'Rectifier tidak valid.',
            'nomor_bank.required'        => 'Nomor Bank Baterai wajib diisi.',
            'merk_battery.required'      => 'Merk Baterai wajib dipilih.',
            'tipe_battery.required'      => 'Tipe Baterai wajib dipilih.',
            'jenis_battery.required'     => 'Jenis Baterai wajib dipilih.',
            'jenis_battery.in'           => 'Jenis Baterai harus Lithium atau VRLA.',
            'kapasitas_battery.required' => 'Kapasitas Baterai wajib dipilih.',
        ];
    }
}
