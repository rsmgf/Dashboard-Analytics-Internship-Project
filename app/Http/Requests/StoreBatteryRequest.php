<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatteryRequest extends FormRequest
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
            'photo_battery'            => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'keterangan_gambar'        => ['nullable', 'string', 'max:255'],

            // Uji Baterai
            'tanggal_uji_terakhir'     => ['nullable', 'date'],
            'tanggal_pemasangan'       => ['nullable', 'date'],
            'tanggal_pemeriksaan'      => ['nullable', 'date'],
            'tanggal_penggantian'      => ['nullable', 'date'],
            'status_uji'               => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pic'                      => 'PIC',
            'rectifier_id'             => 'Nomor Rectifier',
            'nomor_bank'               => 'Nomor Bank Baterai',
            'merk_battery'             => 'Merk Baterai',
            'tipe_battery'             => 'Tipe Baterai',
            'jenis_battery'            => 'Jenis Baterai',
            'kapasitas_battery'        => 'Kapasitas Baterai',
            'kapasitas_uji'            => 'Kapasitas Uji',
            'vrla_1'                   => 'Tegangan Sel VRLA 1',
            'vrla_2'                   => 'Tegangan Sel VRLA 2',
            'vrla_3'                   => 'Tegangan Sel VRLA 3',
            'vrla_4'                   => 'Tegangan Sel VRLA 4',
            'kapasitas_battery_persen' => 'Kapasitas Baterai (%)',
            'performa_baterai'         => 'Performa Baterai',
            'tegangan'                 => 'Tegangan Total',
            'photo_battery'            => 'Foto Baterai',
            'keterangan_gambar'        => 'Keterangan Foto Baterai',
            'tanggal_uji_terakhir'     => 'Tanggal Uji Terakhir',
            'tanggal_pemasangan'       => 'Tanggal Pemasangan',
            'tanggal_pemeriksaan'      => 'Tanggal Pemeriksaan',
            'tanggal_penggantian'      => 'Tanggal Penggantian',
            'status_uji'               => 'Status Uji',
        ];
    }

    public function messages(): array
    {
        return [
            'pic.required'                     => 'Nama PIC wajib diisi.',
            'rectifier_id.required'            => 'Silakan pilih Rectifier yang terhubung.',
            'rectifier_id.integer'             => 'Pilihan Rectifier tidak valid.',
            'rectifier_id.min'                 => 'Pilihan Rectifier tidak valid.',
            'nomor_bank.required'              => 'Nomor Bank Baterai wajib diisi (misal: Bank 1).',
            'merk_battery.required'            => 'Merk Baterai wajib dipilih.',
            'tipe_battery.required'            => 'Tipe Baterai wajib dipilih.',
            'jenis_battery.required'           => 'Jenis Baterai wajib dipilih.',
            'jenis_battery.in'                 => 'Jenis Baterai harus berupa Lithium atau VRLA.',
            'kapasitas_battery.required'       => 'Kapasitas Baterai wajib dipilih.',
            'kapasitas_battery.numeric'        => 'Kapasitas Baterai harus berupa angka.',
            'kapasitas_battery.min'            => 'Kapasitas Baterai minimal bernilai :min Ah.',
            'kapasitas_battery_persen.numeric' => 'Persentase kapasitas harus berupa angka.',
            'kapasitas_battery_persen.min'     => 'Persentase kapasitas tidak boleh bernilai negatif.',
            'tegangan.numeric'                 => 'Tegangan harus berupa angka.',
            'tegangan.min'                     => 'Tegangan tidak boleh bernilai negatif.',
            'tanggal_uji_terakhir.date'        => 'Tanggal Uji Terakhir harus berupa tanggal yang valid.',
            'tanggal_penggantian.date'         => 'Tanggal Penggantian harus berupa tanggal yang valid.',
            'photo_battery.image'              => 'Berkas Foto Baterai harus berupa gambar.',
            'photo_battery.mimes'              => 'Format Foto Baterai harus JPG, JPEG, atau PNG.',
            'photo_battery.max'                => 'Ukuran Foto Baterai maksimal 2 MB.',
            'keterangan_gambar.max'            => 'Keterangan Foto Baterai maksimal :max karakter.',
        ];
    }
}
