<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Categories::latest()->get();
        return view('view-menu-dashboard.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'nullable',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconPath = $icon->storeAs('kategori', Str::slug($request->nama_kategori) . '.' . $icon->getClientOriginalExtension(), 'public');
        }

        Categories::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi,
            'icon' => $iconPath
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Categories $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'nullable',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = [
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi
        ];

        if ($request->hasFile('icon')) {
            // Hapus gambar lama jika ada
            if ($kategori->icon && Storage::disk('public')->exists($kategori->icon)) {
                Storage::disk('public')->delete($kategori->icon);
            }

            $icon = $request->file('icon');
            $iconPath = $icon->storeAs('kategori', Str::slug($request->nama_kategori) . '.' . $icon->getClientOriginalExtension(), 'public');
            $data['icon'] = $iconPath;
        }

        $kategori->update($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Categories $kategori)
    {
        // Hapus gambar icon jika ada
        if ($kategori->icon && Storage::disk('public')->exists($kategori->icon)) {
            Storage::disk('public')->delete($kategori->icon);
        }

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }


    public function exportKategori()
    {
        $excelFileName = 'kategori_export.xlsx';
        Excel::store(new CategoriesExport, $excelFileName, 'public');

        $zipFileName = 'kategori_export.zip';
        $zip = new ZipArchive;
        $zipPath = storage_path("app/public/$zipFileName");

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Add Excel file
            $zip->addFile(storage_path("app/public/$excelFileName"), $excelFileName);

            // Add Icons folder
            $icons = Storage::files('public/kategori');
            foreach ($icons as $icon) {
                $iconName = basename($icon);
                $zip->addFile(storage_path('app/' . $icon), "icon/" . $iconName);
            }

            $zip->close();
        }

        // Delete temp Excel after ZIP done (optional)
        Storage::delete("public/$excelFileName");

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    public function importKategori(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx',
        ]);

        Excel::import(new CategoriesImport, $request->file('excel_file'));

        return back()->with('success', 'Import Kategori berhasil!');
    }
}
