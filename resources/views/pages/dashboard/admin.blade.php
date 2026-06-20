@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Revenue Card -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pendapatan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success-light text-success">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                                        <span class="text-muted small pt-2">Dari transaksi sukses</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Sales Card -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Transaksi Sukses</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary-light text-primary">
                                        <i class="bi bi-cart-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalSales }}</h6>
                                        <span class="text-muted small pt-2">Pesanan berhasil</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pelanggan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger-light text-danger">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalCustomers }}</h6>
                                        <span class="text-muted small pt-2">Pengguna terdaftar</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Customers Card -->

                    <!-- Products Card -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Produk</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning-light text-warning" style="background-color: #fef5e7; color: #f39c12;">
                                        <i class="bi bi-archive"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #f39c12;">{{ $totalProducts }}</h6>
                                        <span class="text-muted small pt-2">Katalog mebel</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Products Card -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Tables Section -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">5 Transaksi Terbaru</h5>
                                <table class="table table-borderless table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col">Invoice ID</th>
                                            <th scope="col">Pelanggan</th>
                                            <th scope="col">Total Harga</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentTransactions as $transaction)
                                            <tr>
                                                <th scope="row">#{{ $transaction->id }}</th>
                                                <td>{{ $transaction->name }}</td>
                                                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
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
                                                <td>
                                                    <a href="{{ route('transaction.show', $transaction->id) }}" class="btn btn-sm btn-info text-white">Show</a>
                                                    <a href="{{ route('transaction.edit', $transaction->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Belum ada transaksi terbaru.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- End Recent Sales -->
                </div>
            </div>

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Top Selling -->
                <div class="card top-selling overflow-auto">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Produk Terlaris</h5>
                        <table class="table table-borderless table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topSelling as $item)
                                    @if ($item->product)
                                        <tr>
                                            <td>
                                                <a href="{{ route('produk.edit', $item->product->slug) }}" class="text-primary fw-bold">
                                                    {{ $item->product->name }}
                                                </a>
                                            </td>
                                            <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                            <td class="fw-bold">{{ $item->total_sold }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Belum ada data penjualan produk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div><!-- End Top Selling -->
            </div>

        </div>
    </section>
@endsection
