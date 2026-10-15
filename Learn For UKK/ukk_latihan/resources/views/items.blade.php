<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>CRUD Data Dummy Laravel 13</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .alert {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>CRUD Data Dummy (Session)</h2>

    @if (session('success'))
        <p class="alert">{{ session('success') }}</p>
    @endif

    <!-- Form Tambah / Edit -->
    <h3>{{ isset($editItem) ? 'Edit Item' : 'Tambah Item' }}</h3>
    <form action="{{ isset($editItem) ? route('items.update', $editItem['id']) : route('items.store') }}" method="POST">
        @csrf
        @if (isset($editItem))
            @method('PUT')
        @endif

        <div class="form-group">
            <label>Nama Item:</label><br>
            <input type="text" name="nama" value="{{ $editItem['nama'] ?? '' }}" required>
        </div>

        <div class="form-group">
            <label>Harga:</label><br>
            <input type="number" name="harga" value="{{ $editItem['harga'] ?? '' }}" required>
        </div>

        <button type="submit">{{ isset($editItem) ? 'Simpan Perubahan' : 'Tambah' }}</button>
        @if (isset($editItem))
            <a href="{{ route('items.index') }}">Batal</a>
        @endif
    </form>

    <hr>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Item</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('items.index', ['edit' => $item['id']]) }}">Edit</a> |
                        <form action="{{ route('items.destroy', $item['id']) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Data kosong.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
