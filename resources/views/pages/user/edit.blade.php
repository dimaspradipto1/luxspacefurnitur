@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Pengguna</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body pt-4">
                        <h5 class="card-title">Form Edit Pengguna</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Nama <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="Masukkan alamat email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div class="mb-3">
                                <label for="roles" class="form-label fw-semibold">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <select id="roles" name="roles"
                                    class="form-select @error('roles') is-invalid @enderror">
                                    <option value="" disabled>-- Pilih Role --</option>
                                    <option value="admin" {{ old('roles', $user->roles) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user"  {{ old('roles', $user->roles) == 'user'  ? 'selected' : '' }}>User</option>
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Separator section password --}}
                            <hr class="my-4">
                            <p class="fw-semibold mb-1">Ubah Password</p>
                            <div class="alert alert-info py-2 px-3 mb-3" role="alert" style="font-size:0.875rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Biarkan kosong jika tidak ingin mengubah password. Password lama akan tetap digunakan.
                            </div>

                            {{-- Password Baru --}}
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter (kosongkan jika tidak diubah)"
                                    oninput="toggleConfirm(this.value)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password — hanya muncul jika password baru diisi --}}
                            <div class="mb-4" id="confirmWrapper" style="display:none;">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Ulangi password baru">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Update
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Tampilkan field konfirmasi hanya jika password baru mulai diisi
    function toggleConfirm(value) {
        const wrapper = document.getElementById('confirmWrapper');
        if (value.length > 0) {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
            document.getElementById('password_confirmation').value = '';
        }
    }

    // Jika terjadi error validasi password, pastikan confirmWrapper tampil
    @if ($errors->has('password') || $errors->has('password_confirmation'))
        document.getElementById('confirmWrapper').style.display = 'block';
    @endif
</script>
@endpush
