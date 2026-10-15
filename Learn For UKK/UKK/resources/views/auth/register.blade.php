<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Halaman Membuat Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Register Akun</h2>
        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2 border rounded-lg">
                @error('name')
                    <p class="text-red-500 pt-1 text-xs">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-3 py-2 border rounded-lg">
                @error('email')
                    <p class="text-red-500 pt-1 text-xs">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg">
                @error('password')
                    <p class="text-red-500 pt-1 text-xs">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Register</button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-600">Sudah punya akun? <a href="{{ route('login') }}"
                class="text-blue-600 hover:underline">Login</a></p>
    </div>
</body>

</html>
