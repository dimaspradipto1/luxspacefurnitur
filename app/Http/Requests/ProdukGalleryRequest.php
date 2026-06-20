<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdukGalleryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $isPost = $this->isMethod('POST');

        if ($isPost) {
            return [
                'products_id' => ['required', 'integer', 'exists:products,id'],
                'url'         => ['required', 'array'],
                'url.*'       => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
                'description' => ['nullable', 'string'],
            ];
        } else {
            return [
                'products_id' => ['required', 'integer', 'exists:products,id'],
                'url'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
                'description' => ['nullable', 'string'],
            ];
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'products_id.required' => 'Produk wajib dipilih.',
            'products_id.exists'   => 'Produk tidak valid.',
            'url.required'         => 'Foto wajib diunggah.',
            'url.array'            => 'Foto harus diunggah.',
            'url.*.image'          => 'Setiap file harus berupa gambar.',
            'url.*.mimes'          => 'Format setiap gambar harus jpeg, png, jpg, gif, atau svg.',
            'url.image'            => 'File harus berupa gambar.',
            'url.mimes'            => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
        ];
    }
}
