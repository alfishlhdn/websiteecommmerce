<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment_methods;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Kurir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $methods = Payment_methods::all();
        return view('view-menu-dashboard.metode-pembayaran', compact('methods'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
        ];

        // Kalau QRIS → payload + optional gambar
        if (strtolower($request->name) === 'qris') {
            $rules['qris_image']   = 'nullable|image|mimes:png,jpg,jpeg|max:2048';
            $rules['qris_payload'] = 'required|string';
        } else {
            // Selain QRIS → code wajib diisi (nomor rekening, nomor e-wallet)
            $rules['code'] = 'required|string|max:255|unique:payment_methods,code';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $path = null;
        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('qris', 'public');
        }

        Payment_methods::create([
            'name'            => $request->name,
            'code'            => strtolower($request->name) === 'qris' ? 'qris' : $request->code,
            'description'     => $request->description,
            'is_active'       => $request->is_active,
            'qris_image_path' => $path,
            'qris_payload'    => strtolower($request->name) === 'qris' ? $request->qris_payload : null,
        ]);

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $m = Payment_methods::findOrFail($id);

        $rules = [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
        ];

        if (strtolower($request->name) === 'qris') {
            $rules['qris_image']   = 'nullable|image|mimes:png,jpg,jpeg|max:2048';
            $rules['qris_payload'] = 'required|string';
        } else {
            $rules['code'] = 'required|string|max:255|unique:payment_methods,code,' . $id;
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $path = $m->qris_image_path;
        if ($request->hasFile('qris_image')) {
            if ($path) Storage::disk('public')->delete($path);
            $path = $request->file('qris_image')->store('qris', 'public');
        }

        $m->update([
            'name'            => $request->name,
            'code'            => strtolower($request->name) === 'qris' ? 'qris' : $request->code,
            'description'     => $request->description,
            'is_active'       => $request->is_active,
            'qris_image_path' => $path,
            'qris_payload'    => strtolower($request->name) === 'qris' ? $request->qris_payload : null,
        ]);

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $m = Payment_methods::findOrFail($id);

        if ($m->qris_image_path) {
            Storage::disk('public')->delete($m->qris_image_path);
        }

        $m->delete();
        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}