<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            /* border: 1px solid red; */
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">
    <div class="flex flex-col w-100 bg-blue-400 rounded-xl p-6 text-white">
        <div class="gap-4">
            <h1 class="text-center font-bold text-xl">
                Login Akun
            </h1>
            <p class="text-center">
                Masuk akun anda
            </p>
        </div>
        <div>
            <form action="{{ route('register-post') }}" method="POST" class="flex flex-col p-2 gap-1">
                @csrf
                <label for="email">
                    Email
                </label>
                <input name="email" type="email"
                    class="p-1 border border-white-300 rounded-lg outline-none focus:border-white"
                    placeholder="Contoh: abcde">
                <!-- </form>

            <form action="" class="flex flex-col p-2 gap-1"> -->
                <label for="password">
                    Password
                </label>
                <input name="password" type="password"
                    class="p-1 border border-white-300 rounded-lg outline-none focus:border-white"
                    placeholder="Contoh: abcde">
                <div class="text-center p-2">
                    <button type="submit"
                        class="bg-white text-blue-400 font-semibold rounded-lg py-2 w-82 hover:bg-blue-50">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</body>

</html>