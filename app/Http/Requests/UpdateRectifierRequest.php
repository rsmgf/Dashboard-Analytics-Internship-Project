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

        $rules = [
            // Informasi dasar (nama_alias auto-generate, tidak bisa diubah)
            'deskripsi'               => 'nullable|string',
            'tanggal_pemeriksaan'     => 'nullable|date',
            'pic'                     => 'nullable|string|max:255',

            // Data teknis utama
            'merk'                    => 'required|string|max:255',
            'type'                    => 'required|string|max:255',
            'sn_rectifier'            => 'required|string|unique:rectifiers,sn_rectifier,' . $rectifierId,
            'kapasitas_slot'          => 'required|integer|min:1|max:100',

            // Data teknis tambahan
            'couple'                  => 'nullable|string|max:255',
            'type_modul_controller'   => 'nullable|string|max:255',
            'type_modul_power'        => 'nullable|string|max:255',
            'kapasitas_rectifier'     => 'nullable|string|max:255',
            'beban'                   => 'nullable|string|max:255',
            'utilisasi'               => 'nullable|numeric|min:0|max:100',
            'foto_rectifier'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Modules (anti-spam: maks 100)
            'modules'                    => 'nullable|array|max:100',
            'modules.*.id'               => 'nullable|integer',
            'modules.*.kapasitas_ampere' => 'nullable|string',

            // Outputs (anti-spam: maks 50)
            'outputs'                    => 'nullable|array|max:50',
            'outputs.*.id'               => 'nullable|integer',
            'outputs.*.nama_mcb'         => 'nullable|string',
            'outputs.*.merk_mcb'         => 'nullable|string',
            'outputs.*.kapasitas_mcb'    => 'nullable|string',
            'outputs.*.peruntukan'       => 'nullable|string',
        ];

        if ($this->has('modules') && is_array($this->modules)) {
            foreach ($this->modules as $key => $mod) {
                $moduleId = $mod['id'] ?? null;
                $uniqueRule = \Illuminate\Validation\Rule::unique('rectifier_modules', 'sn_modul');
                if ($moduleId) {
                    $uniqueRule->ignore($moduleId);
                }
                $rules["modules.{$key}.sn_modul"] = ['nullable', 'string', 'distinct', $uniqueRule];
            }
        } else {
            $rules['modules.*.sn_modul'] = 'nullable|string';
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [
            'merk'                => 'Merk Rectifier',
            'type'                => 'Tipe Rectifier',
            'sn_rectifier'        => 'Serial Number Rectifier',
            'kapasitas_slot'      => 'Kapasitas Slot Modul',
            'foto_rectifier'      => 'Foto Rectifier',
            'pic'                 => 'PIC',
            'tanggal_pemeriksaan' => 'Tanggal Pemeriksaan',
            'deskripsi'           => 'Deskripsi',
        ];

        if ($this->has('modules') && is_array($this->modules)) {
            foreach ($this->modules as $key => $val) {
                $num = (int)$key + 1;
                $attributes["modules.{$key}.sn_modul"] = "Modul {$num} (Serial Number)";
                $attributes["modules.{$key}.kapasitas_ampere"] = "Modul {$num} (Kapasitas Ampere)";
            }
        }

        if ($this->has('outputs') && is_array($this->outputs)) {
            foreach ($this->outputs as $key => $val) {
                $num = (int)$key + 1;
                $attributes["outputs.{$key}.nama_mcb"] = "Output MCB {$num} (Nama)";
            }
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'merk.required'               => 'Merk Rectifier wajib diisi.',
            'type.required'               => 'Tipe Rectifier wajib diisi.',
            'sn_rectifier.required'       => 'Serial Number Rectifier wajib diisi.',
            'sn_rectifier.unique'         => 'Serial Number Rectifier sudah digunakan oleh perangkat lain. Silakan gunakan SN lain.',
            'kapasitas_slot.required'     => 'Kapasitas Slot Modul wajib diisi.',
            'kapasitas_slot.min'          => 'Kapasitas Slot Modul minimal 1.',
            'foto_rectifier.image'        => 'Berkas Foto Rectifier harus berupa gambar.',
            'foto_rectifier.mimes'        => 'Format Foto Rectifier harus JPG, JPEG, atau PNG.',
            'foto_rectifier.max'          => 'Ukuran Foto Rectifier maksimal 2 MB.',
            'modules.*.sn_modul.unique'   => 'Serial Number pada :attribute sudah terdaftar di sistem. Mohon gunakan Serial Number yang berbeda.',
            'modules.*.sn_modul.distinct' => 'Serial Number pada :attribute sama dengan modul lainnya pada form ini. Mohon gunakan Serial Number yang berbeda.',

        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('utilisasi') && is_string($this->input('utilisasi'))) {
            $this->merge([
                'utilisasi' => str_replace(',', '.', $this->input('utilisasi')),
            ]);
        }
    }
}
