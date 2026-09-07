<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKwhRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'building' => ['required', 'string', 'max:100'],
            'pic' => ['required', 'string', 'max:100'],
            'type_pop' => ['required', 'string', 'max:50'],
            'id_customer_pln' => ['required', 'string', 'max:50'],
            'tanggal_pemeriksaan' => ['required', 'date'],

            // 'daya_ps_gi' => ['required', 'string', 'max:50'],
            'mcb_utama' => ['required', 'numeric'],
            'jumlah_phasa' => ['required', 'in:1 Phasa,3 Phasa'],
            'keberadaan_arrester' => ['required', 'in:ADA,TIDAK ADA'],
            'merk_type_arrester' => ['nullable', 'string', 'max:100'],
            // 'status_kelistrikan' => ['required', 'in:OK / Memadai,Perlu Perbaikan,Kritis'],

            'teg_rn' => ['required', 'numeric'],
            'arus_r' => ['required', 'numeric'],
            'teg_sn' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'arus_s' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'teg_tn' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'arus_t' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'teg_rs' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'teg_st' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'teg_rt' => ['nullable', 'numeric', 'required_if:jumlah_phasa,3 Phasa'],
            'teg_ng' => ['required', 'numeric'],
            'total_daya_terpakai' => ['nullable', 'numeric'],
            'total_beban' => ['nullable', 'numeric'],

            'warna_r' => ['required', 'string', 'max:50'],
            'warna_s' => ['required', 'string', 'max:50'],
            'warna_t' => ['required', 'string', 'max:50'],
            'warna_n' => ['required', 'string', 'max:50'],
            'warna_g' => ['required', 'string', 'max:50'],
            'ukuran_r' => ['required', 'string', 'max:50'],
            'ukuran_s' => ['required', 'string', 'max:50'],
            'ukuran_t' => ['required', 'string', 'max:50'],
            'ukuran_n' => ['required', 'string', 'max:50'],
            'ukuran_g' => ['required', 'string', 'max:50'],

            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'captions' => ['required', 'array', 'min:1'],
            'captions.*' => ['required', 'string', 'max:150'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $decimalFields = [
            'mcb_utama',
            'teg_rn',
            'teg_sn',
            'teg_tn',
            'teg_rs',
            'teg_st',
            'teg_rt',
            'teg_ng',
            'arus_r',
            'arus_s',
            'arus_t',
            'total_daya_terpakai',
            'total_beban',
        ];

        $normalized = [];
        foreach ($decimalFields as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $normalized[$field] = str_replace(',', '.', $this->input($field));
            }
        }

        $this->merge($normalized);
    }
}
