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
            'foto_asset' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'tanggal_pembelian' => ['nullable', 'date'],
            'harga' => ['nullable', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_asset.required' => 'Nama asset wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'kondisi.in' => 'Kondisi tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'foto_asset.image' => 'File harus berupa gambar.',
            'foto_asset.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'foto_asset.max' => 'Ukuran foto maksimal 2MB.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
        ];
    }
}
