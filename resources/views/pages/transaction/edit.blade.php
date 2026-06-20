@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transaction.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body pt-4">
                        <h5 class="card-title">Form Edit Transaksi</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('transaction.update', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Bagian Kiri: Hubungan Data (User & Produk) -->
                                <div class="col-md-6 border-end">
                                    <h6 class="fw-semibold text-primary mb-3">1. Informasi Hubungan</h6>

                                    <div class="mb-3">
                                        <label for="users_id" class="form-label fw-semibold">
                                            Pilih Pengguna (Pembeli) <span class="text-danger">*</span>
                                        </label>
                                        <select name="users_id" id="users_id" class="form-select @error('users_id') is-invalid @enderror">
                                            <option value="" disabled>-- Pilih Pengguna --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" 
                                                        data-name="{{ $user->name }}" 
                                                        data-email="{{ $user->email }}"
                                                        {{ old('users_id', $transaction->users_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('users_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold d-block">
                                            Pilih Produk <span class="text-danger">*</span>
                                        </label>
                                        <div class="border rounded p-3 @error('products_id') border-danger @enderror" style="max-height: 250px; overflow-y: auto;">
                                            @foreach($products as $product)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input product-checkbox" type="checkbox" 
                                                           name="products_id[]" value="{{ $product->id }}" 
                                                           id="product_{{ $product->id }}" 
                                                           data-price="{{ $product->price }}"
                                                           {{ is_array(old('products_id', $transactionProducts)) && in_array($product->id, old('products_id', $transactionProducts)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="product_{{ $product->id }}">
                                                        {{ $product->name }} <span class="text-muted">(Rp {{ number_format($product->price, 0, ',', '.') }})</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('products_id')
                                            <div class="text-danger mt-1" style="font-size: 0.875rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bagian Kanan: Detail Transaksi & Pengiriman -->
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-primary mb-3">2. Detail Transaksi</h6>

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            Nama Penerima <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $transaction->name) }}"
                                            placeholder="Masukkan nama penerima">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label fw-semibold">
                                                Email Penerima <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" id="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $transaction->email) }}"
                                                placeholder="Masukkan email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label fw-semibold">
                                                Nomor Telepon <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="phone" name="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', $transaction->phone) }}"
                                                placeholder="Masukkan nomor telepon">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label fw-semibold">
                                            Alamat Pengiriman <span class="text-danger">*</span>
                                        </label>
                                        <textarea id="address" name="address" rows="3"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="Masukkan alamat lengkap">{{ old('address', $transaction->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="courier" class="form-label fw-semibold">Kurir</label>
                                            <input type="text" id="courier" name="courier"
                                                class="form-control @error('courier') is-invalid @enderror"
                                                value="{{ old('courier', $transaction->courier) }}"
                                                placeholder="Contoh: JNE, TIKI, POS">
                                            @error('courier')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="payment" class="form-label fw-semibold">
                                                Metode Pembayaran <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="payment" name="payment"
                                                class="form-control @error('payment') is-invalid @enderror"
                                                value="{{ old('payment', $transaction->payment) }}"
                                                placeholder="Masukkan metode pembayaran">
                                            @error('payment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_url" class="form-label fw-semibold">URL Pembayaran (Opsional)</label>
                                        <input type="url" id="payment_url" name="payment_url"
                                            class="form-control @error('payment_url') is-invalid @enderror"
                                            value="{{ old('payment_url', $transaction->payment_url) }}"
                                            placeholder="https://example.com/payment">
                                        @error('payment_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="total_price" class="form-label fw-semibold">
                                                Total Harga <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" id="total_price" name="total_price"
                                                    class="form-control @error('total_price') is-invalid @enderror"
                                                    value="{{ old('total_price', $transaction->total_price) }}" min="0">
                                            </div>
                                            @error('total_price')
                                                <div class="text-danger mt-1" style="font-size:0.875rem;">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="status" class="form-label fw-semibold">
                                                Status Transaksi <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                                <option value="PENDING" {{ old('status', $transaction->status) == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                                <option value="SUCCESS" {{ old('status', $transaction->status) == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                                                <option value="CHALLENGE" {{ old('status', $transaction->status) == 'CHALLENGE' ? 'selected' : '' }}>CHALLENGE</option>
                                                <option value="FAILED" {{ old('status', $transaction->status) == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                                                <option value="SHIPPING" {{ old('status', $transaction->status) == 'SHIPPING' ? 'selected' : '' }}>SHIPPING</option>
                                                <option value="DELIVERED" {{ old('status', $transaction->status) == 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                                                <option value="CANCELLED" {{ old('status', $transaction->status) == 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3 border-top pt-3">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('transaction.index') }}" class="btn btn-secondary px-4">
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
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('users_id');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        
        // Auto populate user info
        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if(selectedOption.value) {
                nameInput.value = selectedOption.getAttribute('data-name') || '';
                emailInput.value = selectedOption.getAttribute('data-email') || '';
            }
        });

        // Auto calculation for total price
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const totalPriceInput = document.getElementById('total_price');

        function calculateTotalPrice() {
            let total = 0;
            productCheckboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.getAttribute('data-price')) || 0;
                }
            });
            totalPriceInput.value = total;
        }

        productCheckboxes.forEach(cb => {
            cb.addEventListener('change', calculateTotalPrice);
        });
    });
</script>
@endpush
