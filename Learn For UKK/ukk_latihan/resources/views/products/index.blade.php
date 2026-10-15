@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Manajemen Produk
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data produk yang terdaftar dalam sistem.
            </p>
        </div>

        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5
                    text-sm font-semibold text-white bg-blue-600 rounded-lg
                    hover:bg-blue-700">
            <span class="text-lg leading-none">+</span>
            Tambah Produk
        </a>
    </div>

    <div>
        <table class="width-fill">
            <tr>
                <th>apel</th>
                <th>apel</th>
                <th>apel</th>
                <th>apel</th>
            </tr>
        </table>
    </div>

@endsection