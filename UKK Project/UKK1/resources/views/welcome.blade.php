<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-4 font-semibold text-gray-600">Kode Produk</th>
                    <th class="p-4 font-semibold text-gray-600">Nama Produk</th>
                    <th class="p-4 font-semibold text-gray-600">Harga Produk</th>
                    <th class="p-4 font-semibold text-gray-600">Gambar Produk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($initialDatas as $initialData)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $initialData['code'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
