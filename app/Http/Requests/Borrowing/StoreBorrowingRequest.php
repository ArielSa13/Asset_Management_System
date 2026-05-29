<?php

namespace App\Http\Requests\Borrowing;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public access allowed
    }

    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'borrower_email' => ['required', 'email:rfc,dns', 'max:255'],
            'borrower_phone' => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/', 'max:20'],
            'purpose' => ['required', 'string', 'max:1000'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['nullable', 'date', 'after:borrow_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'borrower_email.required' => 'Email wajib diisi.',
            'borrower_email.email' => 'Format email tidak valid.',
            'borrower_phone.required' => 'Nomor telepon wajib diisi.',
            'borrower_phone.regex' => 'Format nomor telepon tidak valid. Contoh: 08123456789 atau +628123456789',
            'purpose.required' => 'Tujuan peminjaman wajib diisi.',
            'borrow_date.required' => 'Tanggal pinjam wajib diisi.',
            'borrow_date.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'return_date.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ];
    }
}
