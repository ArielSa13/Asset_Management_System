<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'prefix' => ['required', 'string', 'max:10', 'alpha', 'unique:categories,prefix'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'prefix.required' => 'Prefix wajib diisi.',
            'prefix.alpha' => 'Prefix hanya boleh berisi huruf.',
            'prefix.unique' => 'Prefix sudah digunakan.',
            'prefix.max' => 'Prefix maksimal 10 karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('prefix')) {
            $this->merge(['prefix' => strtoupper($this->prefix)]);
        }
    }
}
