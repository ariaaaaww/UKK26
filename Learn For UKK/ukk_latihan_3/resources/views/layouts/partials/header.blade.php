{{-- =========================================================================
     NAVBAR UTAMA KOPI SENJA
     Memuat logo, link menu, riwayat, dan penghitung dinamis total item dalam keranjang
     ========================================================================= --}}
<nav class="bg-amber-950 text-amber-50 shadow-md sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-4 flex flex-wrap justify-between items-center gap-4">
        {{-- Logo dan Nama Kafe --}}
        <a href="{{ route('menus.index') }}" class="flex items-center gap-2 group">
            <span class="text-2xl">☕</span>
            <span class="text-xl font-bold tracking-tight text-amber-100 group-hover:text-amber-300 transition-colors">
                Kopi Senja
            </span>
        </a>

        {{-- Navigasi Menu Header --}}
        <div class="flex items-center gap-2 sm:gap-4 text-sm font-medium">
            {{-- Link Halaman Katalog Menu --}}
            <a href="{{ route('menus.index') }}"
                class="px-3 py-2 rounded-lg hover:bg-amber-900 transition-colors {{ request()->routeIs('menus.*') ? 'bg-amber-900 text-amber-200' : 'text-amber-100' }}">
                Katalog Menu
            </a>

            {{-- Link Halaman Riwayat Transaksi --}}
            <a href="{{ route('orders.index') }}"
                class="px-3 py-2 rounded-lg hover:bg-amber-900 transition-colors {{ request()->routeIs('orders.*') ? 'bg-amber-900 text-amber-200' : 'text-amber-100' }}">
                Riwayat Pesanan
            </a>

            {{-- Link Keranjang Belanja dengan Badge Jumlah Qty Item --}}
            @php
                // Menghitung akumulasi kuantitas seluruh item yang ada di keranjang session
                $cartCount = (int) collect(session('cart', []))->sum('quantity');
            @endphp
            <a href="{{ route('cart.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-800 hover:bg-amber-700 text-white font-semibold transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Keranjang</span>
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-amber-950 bg-amber-300 rounded-full">
                    {{ $cartCount }}
                </span>
            </a>
        </div>
    </div>
</nav>
