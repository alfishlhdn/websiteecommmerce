<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $customers = User::withCount('orders')
                        ->withSum('orders', 'total')
                        ->when($search, function($query, $search) {
                            return $query->where('name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%");
                        })
                        ->paginate(10);

        return view('view-menu-dashboard.datapelanggan', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
        ]);

        User::create($request->only('name', 'email', 'phone'));
        return back()->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'required',
        ]);

        $customer->update($request->only('name', 'email', 'phone'));
        return back()->with('success', 'Data pelanggan diperbarui');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return back()->with('success', 'Pelanggan berhasil dihapus');
    }
}
