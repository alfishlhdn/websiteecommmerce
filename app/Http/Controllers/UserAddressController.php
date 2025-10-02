<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_Addresses;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'telepon' => 'required|string|max:30',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kelurahan' => 'required|string'
        ]);

        $addr = User_Addresses::create(array_merge($request->only([
            'label','telepon','alamat_lengkap','kecamatan','kota','provinsi','kelurahan'
        ]), ['user_id' => Auth::id()]));

        return response()->json(['success' => true, 'address' => $addr]);
    }
}
