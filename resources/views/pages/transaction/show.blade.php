@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Detail Transaksi #{{ $transaction->id }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transaction.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="mb-3">
        <a href="{{ route('transaction.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        @if (auth()->user()->roles === 'admin')
            <a href="{{ route('transaction.edit', $transaction->id) }}" class="btn btn-warning btn-sm float-end">
                <i class="bi bi-pencil"></i> Edit Transaksi
            </a>
        @endif
    </div>

    <section class="section">
        <div class="row">
            <!-- Left Side: Transaction Details -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pengiriman & Penerima</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Penerima</div>
                            <div class="col-md-8">{{ $transaction->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Email</div>
                            <div class="col-md-8">{{ $transaction->email }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Telepon</div>
                            <div class="col-md-8">{{ $transaction->phone }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Alamat Pengiriman</div>
                            <div class="col-md-8">{{ $transaction->address }}</div>
                        </div>

                        <hr>

                        <h5 class="card-title">Metode Pembayaran & Kurir</h5>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Kurir Pengiriman</div>
                            <div class="col-md-8">{{ strtoupper($transaction->courier) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Metode Pembayaran</div>
                            <div class="col-md-8">{{ $transaction->payment }}</div>
                        </div>

                        @if ($transaction->payment_url)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Link Pembayaran</div>
                                <div class="col-md-8">
                                    <a href="{{ $transaction->payment_url }}" target="_blank" class="btn btn-primary btn-xs text-white">
                                        Bayar Sekarang <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status Pesanan</div>
                            <div class="col-md-8">
                                @php
                                    $status = strtoupper($transaction->status);
                                    $badge = 'bg-secondary';
                                    if ($status === 'PENDING') $badge = 'bg-warning text-dark';
                                    elseif ($status === 'SUCCESS' || $status === 'DELIVERED') $badge = 'bg-success';
                                    elseif ($status === 'FAILED') $badge = 'bg-danger';
                                    elseif ($status === 'SHIPPING') $badge = 'bg-primary';
                                @endphp
                                <span class="badge {{ $badge }} fs-6">{{ $status }}</span>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4 fw-bold fs-5">Total Harga</div>
                            <div class="col-md-8 fs-4 fw-bold text-success">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Side: Items Purchased -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Produk yang Dibeli</h5>
                        
                        <div class="list-group list-group-flush">
                            @forelse ($transaction->items as $item)
                                @if ($item->product)
                                    <div class="list-group-item px-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <!-- Product Image Preview -->
                                            <div class="me-3">
                                                @if($item->product->galleries->isNotEmpty())
                                                    <img src="{{ asset('storage/' . $item->product->galleries->first()->url) }}" 
                                                         class="img-thumbnail rounded" 
                                                         style="width: 80px; height: 80px; object-fit: cover;" 
                                                         alt="{{ $item->product->name }}">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center border rounded" 
                                                         style="width: 80px; height: 80px;">
                                                        <i class="bi bi-image text-muted fs-3"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Product Info -->
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-dark">{{ $item->product->name }}</h6>
                                                <div class="text-success fw-semibold">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="list-group-item px-0 py-3 text-muted">
                                        Produk telah dihapus atau tidak ditemukan.
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Tidak ada item dalam transaksi ini.
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
