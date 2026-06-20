<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
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

        return [
            'users_id'    => ['required', 'integer', 'exists:users,id'],
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255'],
            'phone'       => ['required', 'string', 'max:255'],
            'address'     => ['required', 'string'],
            'courier'     => ['nullable', 'string', 'max:255'],
            'payment'     => ['required', 'string', 'max:255'],
            'payment_url' => ['nullable', 'url', 'max:255'],
            'total_price' => ['required', 'integer', 'min:0'],
            'status'      => ['required', 'string', 'in:PENDING,SUCCESS,FAILED,CANCELLED,CHALLENGE,SHIPPING,DELIVERED'],
            'products_id' => [$isPost ? 'required' : 'nullable', 'array'],
            'products_id.*' => ['exists:products,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'users_id.required'    => 'Pengguna wajib dipilih.',
            'users_id.exists'      => 'Pengguna tidak valid.',
            'name.required'        => 'Nama penerima wajib diisi.',
            'email.required'       => 'Email penerima wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'phone.required'       => 'Nomor telepon wajib diisi.',
            'address.required'     => 'Alamat wajib diisi.',
            'payment.required'     => 'Metode pembayaran wajib diisi.',
            'payment_url.url'      => 'Format URL pembayaran tidak valid.',
            'total_price.required' => 'Total harga wajib diisi.',
            'total_price.integer'  => 'Total harga harus berupa angka.',
            'total_price.min'      => 'Total harga tidak boleh negatif.',
            'status.required'      => 'Status transaksi wajib diisi.',
            'status.in'            => 'Status transaksi tidak valid.',
            'products_id.required' => 'Minimal pilih satu produk.',
            'products_id.array'    => 'Format produk tidak valid.',
            'products_id.*.exists' => 'Produk yang dipilih tidak valid.',
        ];
    }
}
