<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>


        <li class="nav-heading">Data Produk</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('produk.*') ? '' : 'collapsed' }}" href="{{ route('produk.index') }}">
                <i class="bi bi-archive"></i>
                <span>Produk</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('product-gallery.*') ? '' : 'collapsed' }}" href="{{ route('product-gallery.index') }}">
                <i class="bi bi-card-image"></i>
                <span>Foto Produk</span>
            </a>
        </li>

        <li class="nav-heading">Data Transaksi</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('transaction.*') ? '' : 'collapsed' }}" href="{{ route('transaction.index') }}">
                <i class="bi bi-shop"></i>
                <span>Transaksi</span>
            </a>
        </li>

        <li class="nav-heading">Data Pengguna</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.*') ? '' : 'collapsed' }}" href="{{ route('user.index') }}">
                <i class="bi bi-people"></i>
                <span>Pengguna</span>
            </a>
        </li>

    </ul>

</aside>
