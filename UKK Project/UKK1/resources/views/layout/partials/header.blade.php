{{-- Header Start --}}
<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center space-x-8">
            <a href="#" class="font-bold text-xl text-blue-600">ASTRA LMS</a>
            <nav class="hidden md:flex space-x-4">
                <a href=""></a>
            </nav>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-700 font-medium">{{ auth()->user()->name ?? 'User' }}
                ({{ auth()->user()->role ?? 'Role' }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
            </form>
        </div>
    </div>
</header>
{{-- Header End --}}
