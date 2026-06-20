@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Pelanggan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-1">Selamat Datang Kembali, {{ auth()->user()->name }}!</h3>
                    <p class="mb-0 opacity-75">Pantau status pesanan furniture impian Anda di sini.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Orders Card -->
                    <div class="col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pesanan Saya</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary-light text-primary">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalOrders }}</h6>
                                        <span class="text-muted small pt-2">Transaksi diajukan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Orders Card -->

                    <!-- Total Spent Card -->
                    <div class="col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Belanjaan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success-light text-success">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Rp {{ number_format($totalSpent, 0, ',', '.') }}</h6>
                                        <span class="text-muted small pt-2">Transaksi sukses</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Spent Card -->

                    <!-- Pending Card -->
                    <div class="col-md-4">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Pesanan Pending</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning-light text-warning" style="background-color: #fef5e7; color: #f39c12;">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #f39c12;">{{ $pendingOrders }}</h6>
                                        <span class="text-muted small pt-2">Menunggu pembayaran</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Pending Card -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Recent Orders Table -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">5 Pesanan Terbaru</h5>
                        <table class="table table-borderless table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Invoice ID</th>
                                    <th scope="col">Penerima</th>
                                    <th scope="col">Kurir</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $transaction)
                                    <tr>
                                        <th scope="row">#{{ $transaction->id }}</th>
                                        <td>{{ $transaction->name }}</td>
                                        <td>{{ strtoupper($transaction->courier) }}</td>
                                        <td class="fw-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $status = strtoupper($transaction->status);
                                                $badge = 'bg-secondary';
                                                if ($status === 'PENDING') $badge = 'bg-warning text-dark';
                                                elseif ($status === 'SUCCESS' || $status === 'DELIVERED') $badge = 'bg-success';
                                                elseif ($status === 'FAILED') $badge = 'bg-danger';
                                                elseif ($status === 'SHIPPING') $badge = 'bg-primary';
                                            @endphp
                                            <span class="badge {{ $badge }}">{{ $status }}</span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $transaction->id) }}" class="btn btn-sm btn-info text-white">Detail Pesanan</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-cart-x fs-2 d-block mb-2"></i>
                                            Anda belum memiliki transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- End Recent Orders Table -->

        </div>
    </section>
@endsection
