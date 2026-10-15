<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-lg font-bold text-gray-900">UKK Latihan</h1>
                <p class="text-xs text-gray-500">ga tauu</p>
            </div>
        </div>

        <div class="flex gap-6 font-semibold">
            <!-- <a href="">Dealer</a>
            <a href="">Area</a>
            <a href="">Department</a>
            <a href="">Assignment</a> -->
        </div>

        <!-- User Profile -->
        <div class="flex items-center gap-4">

            <!-- User Info -->
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-800">
                    Akun
                </p>
                <p class="text-xs text-gray-500">
                    Role
                </p>
            </div>

            <!-- Divider -->
            <div class="w-px h-8 bg-gray-200"></div>

            <!-- Logout -->
            <form action="" method="post">
                @csrf
                <button type="submit" class="flex border border-gray-300 items-center gap-2 px-3 py-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                    Logout
                </button>
            </form>

        </div>
    </div>
</header>