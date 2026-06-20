<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // route('produk') me-resolve model Product via slug, tapi ->id tetap bisa diambil
        $productId = $this->route('produk')?->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            // slug di-generate otomatis, validasi unique diabaikan untuk produk yang diedit
            'slug'        => ['nullable', 'string', 'unique:products,slug,' . ($productId ?? 'NULL')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nama produk wajib diisi.',
            'name.max'             => 'Nama produk maksimal 255 karakter.',
            'price.required'       => 'Harga wajib diisi.',
            'price.integer'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'description.required' => 'Deskripsi wajib diisi.',
        ];
    }

    /**
     * Auto-generate slug dari name sebelum validasi.
     * Slug tidak perlu diinput user — diambil otomatis dari nama produk.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
