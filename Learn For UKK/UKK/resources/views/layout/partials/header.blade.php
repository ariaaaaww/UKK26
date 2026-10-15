<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('products.index') }}">
            <i class="bi bi-box-seam me-2"></i>SimpanBarang
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        <i class="bi bi-list-task me-1"></i> Daftar Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}"
                        href="{{ route('products.create') }}">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Produk
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
