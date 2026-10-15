<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Data dummy awal
    private $initialData = [
        ['id' => 1, 'nama' => 'Laptop', 'harga' => 12000000],
        ['id' => 2, 'nama' => 'Mouse', 'harga' => 250000],
        ['id' => 3, 'nama' => 'Keyboard', 'harga' => 750000],
    ];

    // Helper untuk mengambil data dari session
    private function getItems(Request $request)
    {
        if (!$request->session()->has('items')) {
            $request->session()->put('items', $this->initialData);
        }
        return $request->session()->get('items');
    }

    // READ: Tampil Data & Form
    public function index(Request $request)
    {
        $items = $this->getItems($request);
        $editItem = null;

        // Jika sedang dalam mode edit
        if ($request->has('edit')) {
            $editId = $request->query('edit');
            $editItem = collect($items)->firstWhere('id', $editId);
        }

        return response()->view('items', compact('items', 'editItem'));
    }

    // CREATE: Tambah Data
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);

        $items = $this->getItems($request);

        $newItem = [
            'id' => count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1,
            'nama' => $request->nama,
            'harga' => $request->harga,
        ];

        $items[] = $newItem;
        $request->session()->put('items', $items);

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan!');
    }

    // UPDATE: Perbarui Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);

        $items = $this->getItems($request);

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item['nama'] = $request->nama;
                $item['harga'] = $request->harga;
                break;
            }
        }

        $request->session()->put('items', $items);

        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui!');
    }

    // DELETE: Hapus Data
    public function destroy(Request $request, $id)
    {
        $items = $this->getItems($request);

        $items = array_filter($items, function ($item) use ($id) {
            return $item['id'] != $id;
        });

        // Re-index array
        $request->session()->put('items', array_values($items));

        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus!');
    }
}