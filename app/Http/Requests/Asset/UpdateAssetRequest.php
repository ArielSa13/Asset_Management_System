<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_asset' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'kondisi' => ['required', 'in:baik,cukup,rusak_ringan,rusak_berat'],
            'status' => ['required', 'in:available,borrowed,maintenance,broken,lost'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}
