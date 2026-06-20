<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email,' . $userId],
            // Nullable: jika kosong, password lama tetap dipakai
            'password'              => ['nullable', 'string', Password::min(8), 'confirmed'],
            // Wajib hanya jika password diisi
            'password_confirmation' => ['nullable', 'required_with:password'],
            'roles'                 => ['required', 'in:admin,user'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                    => 'Nama wajib diisi.',
            'email.required'                   => 'Email wajib diisi.',
            'email.email'                      => 'Format email tidak valid.',
            'email.unique'                     => 'Email sudah digunakan pengguna lain.',
            'password.min'                     => 'Password minimal 8 karakter.',
            'password.confirmed'               => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required_with' => 'Konfirmasi password wajib diisi jika password baru dimasukkan.',
            'roles.required'                   => 'Role wajib dipilih.',
            'roles.in'                         => 'Role harus admin atau user.',
        ];
    }
}
