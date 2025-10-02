<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Stok;
use Illuminate\Http\Request;
use App\Exports\ProductStockExport;
use App\Imports\ProductStockImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductStockController extends Controller
{
    public function index()
    {
        $stocks = Product_Stok::with('product.category')->get();
        $products = Product::all();
        return view('view-menu-dashboard.stok', compact('stocks', 'products'));
    }


    public function show(){

    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'tipe' => 'required|string|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $product = Product::find($request->product_id);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        // Cek stok jika tipe 'keluar'
        if ($request->tipe === 'keluar' && $product->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup. Stok saat ini: ' . $product->stok);
        }

        // Simpan stok
        Product_Stok::create($request->all());

        // Update stok produk
        if ($request->tipe === 'masuk') {
            $product->stok += $request->jumlah;
        } elseif ($request->tipe === 'keluar') {
            $product->stok -= $request->jumlah;
        }
        $product->save();

        return back()->with('success', 'Stok berhasil ditambahkan.');
    }

    public function update(Request $request, Product_Stok $stok)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'tipe' => 'required|string|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $product = Product::find($stok->product_id);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $oldJumlah = $stok->jumlah;
        $oldTipe = $stok->tipe;
        $newJumlah = $request->jumlah;
        $newTipe = $request->tipe;

        // Hitung stok sementara
        $stokSementara = $product->stok;

        // Kembalikan efek lama
        if ($oldTipe === 'masuk') {
            $stokSementara -= $oldJumlah;
        } elseif ($oldTipe === 'keluar') {
            $stokSementara += $oldJumlah;
        }

        // Terapkan efek baru
        if ($newTipe === 'masuk') {
            $stokSementara += $newJumlah;
        } elseif ($newTipe === 'keluar') {
            $stokSementara -= $newJumlah;
        }

        // Cek stok tidak negatif
        if ($stokSementara < 0) {
            return back()->with('error', 'Perubahan gagal: stok akan menjadi negatif (saat ini: ' . $product->stok . ').');
        }

        // Simpan update
        $stok->update($request->all());
        $product->stok = $stokSementara;
        $product->save();

        return back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(Product_Stok $stok)
    {
        $product = Product::find($stok->product_id);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        // Kembalikan stok
        if ($stok->tipe === 'masuk') {
            $newStok = $product->stok - $stok->jumlah;
        } else {
            $newStok = $product->stok + $stok->jumlah;
        }

        if ($newStok < 0) {
            return back()->with('error', 'Penghapusan gagal: stok akan menjadi negatif.');
        }

        $product->stok = $newStok;
        $product->save();
        $stok->delete();

        return back()->with('success', 'Stok berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new ProductStockExport, 'stok_produk.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new \App\Imports\ProductStockImport();

        try {
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return back()
                ->with('error', 'Import gagal: format file tidak valid atau rusak.')
                ->with('import_errors', [$e->getMessage()]);
        }

        $summary = "Import selesai. Berhasil: {$import->inserted}, Dilewati: {$import->skipped}, Diproses: {$import->processed}.";

        if (!empty($import->errors)) {
            // Sukses sebagian: tampilkan ringkasan + detail alasan dilewati per baris
            return back()
                ->with('success', $summary)
                ->with('import_errors', $import->errors);
        }

        return back()->with('success', $summary);
    }



}
