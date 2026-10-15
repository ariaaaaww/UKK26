<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="mx-5 my-5">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg">
        <div class="flex justify-center items-center mb-6">
            <h1 class="text-2xl text-black font-bold">Daftar Produk</h1>
        </div>

        <div class="overflow-x-auto">
            <!-- Menambahkan border dan border-gray-300 pada tabel -->
            <table class="w-full text-left border-collapse border border-gray-300">
                <thead>
                    <tr
                        class="bg-gray-100 border-b border-gray-300 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3 border border-gray-300 text-center">No.</th>
                        <th class="px-4 py-3 border border-gray-300">Kode</th>
                        <th class="px-4 py-3 border border-gray-300">Nama</th>
                        <th class="px-4 py-3 border border-gray-300">Tanggal Kadaluarsa</th>
                        <th class="px-4 py-3 border border-gray-300 text-center">Gambar</th>
                        <th class="px-4 py-3 border border-gray-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 border border-gray-300 text-center font-medium text-gray-600">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 border border-gray-300">
                                <input type="number" form="form-{{ $product['id'] }}" name="code"
                                    class="border border-gray-300 px-2 py-1 rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    value="{{ $product['code'] }}">
                            </td>
                            <td class="px-4 py-3 border border-gray-300">
                                <input type="text" form="form-{{ $product['id'] }}" name="name"
                                    value="{{ $product['name'] }}"
                                    class="border border-gray-300 px-2 py-1 rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </td>
                            <td class="text-center">
                                <span class="h-10">
                                    {{ $product['days_left'] }} bulan lagi
                                </span>
                                <input type="hidden" form="form-{{ $product['id'] }}" name="date"
                                    value="{{ $product['date'] }}">
                            </td>
                            <td class="px-4 py-3 border border-gray-300">
                                <form id="form-{{ $product['id'] }}"
                                    action="{{ route('products.update', $product['id']) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex flex-col items-center gap-2">
                                        @if (!empty($product['image']))
                                            <img id="preview-{{ $product['id'] }}"
                                                src="{{ asset('storage/' . $product['image']) }}"
                                                alt="{{ $product['name'] }}"
                                                class="w-20 h-20 object-cover rounded border border-gray-300 shadow-sm">
                                        @else
                                            <img id="preview-{{ $product['id'] }}"
                                                src="https://placehold.co/400x300?text=Tidak+ada+gambar"
                                                alt="{{ $product['name'] }}"
                                                class="w-20 h-20 object-cover rounded border border-gray-300 shadow-sm">
                                        @endif

                                        <input type="file" name="image" accept="image/*"
                                            class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 w-44"
                                            onchange="previewImage(this, 'preview-{{ $product['id'] }}')">
                                    </div>
                                </form>
                            </td>
                            <td class="px-4 py-3 border border-gray-300 text-center">
                                <button type="submit" form="form-{{ $product['id'] }}"
                                    class="px-4 py-2 rounded bg-gray-700 text-white text-sm font-medium hover:bg-gray-900 transition-colors cursor-pointer">
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-4 py-6 border border-gray-300 text-center text-gray-500 font-medium">
                                Data produk kosong.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const file = input.files && input.files[0];
            if (file) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = URL.createObjectURL(file);
                }
            }
        }
    </script>
</body>

</html>
