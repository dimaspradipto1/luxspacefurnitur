<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'roles'    => $request->roles,
        ]);

        Alert::success('Berhasil', 'Pengguna berhasil ditambahkan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('user.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * Password: jika kosong → pakai password lama, jika diisi → ganti dengan hash baru
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Data yang selalu diupdate
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'roles' => $request->roles,
        ];

        // Hanya update password jika field password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        // Jika password kosong → $data tidak menyertakan 'password'
        // sehingga password lama di database tetap tidak berubah

        $user->update($data);

        Alert::success('Berhasil', 'Pengguna berhasil diperbarui.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        Alert::success('Berhasil', 'Pengguna berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('user.index');
    }
}
