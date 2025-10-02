<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function index()
    {
        $kurirs = Kurir::orderBy('id', 'desc')->get();
        return view('view-menu-dashboard.kurir', compact('kurirs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'service_code' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        Kurir::create($request->only(['name', 'service_type', 'price','service_code','keterangan']));

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function update(Request $request, Kurir $kurir)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'service_code' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        $kurir->update($request->only(['name', 'service_type', 'price','service_code','keterangan']));

        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil diperbarui.');
    }

    public function destroy(Kurir $kurir)
    {
        $kurir->delete();
        return redirect()->route('kurir.index')->with('success', 'Kurir berhasil dihapus.');
    }
}
