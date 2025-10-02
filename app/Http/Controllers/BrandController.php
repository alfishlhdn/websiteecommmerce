<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('view-menu-dashboard.brand', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        Brand::create(['name' => $request->name]);

        return back()->with('success', 'Brand berhasil ditambahkan.');
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $brand->update(['name' => $request->name]);

        return back()->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back()->with('success', 'Brand berhasil dihapus.');
    }
}
