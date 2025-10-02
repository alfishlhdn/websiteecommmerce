<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store_Setiing;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreSettingsController extends Controller
{
    public function index()
    {
        $setting = Store_Setiing::first();
        $banners = Banner::all();
        return view('view-menu-dashboard.pengaturan', compact('setting', 'banners'));
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'store_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'logo' => 'nullable|image|max:2048',
            'address_ingooglemaps' => 'required',
        ]);

        $data = $request->only(['store_name', 'email', 'phone','address','address_ingooglemaps']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Store_Setiing::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Informasi toko berhasil diperbarui.');
    }

    public function addBanner(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'link' => 'nullable|url',
            'is_active' => 'boolean'
        ]);

        $data = $request->only(['title', 'description', 'link', 'is_active']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->back()->with('success', 'Banner berhasil ditambahkan.');
    }


    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->back()->with('success', 'Banner berhasil dihapus.');
    }

    public function toggleBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return redirect()->back()->with('success', 'Status banner berhasil diperbarui.');
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->link = $request->link;
        $banner->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($banner->image && Storage::exists($banner->image)) {
                Storage::delete($banner->image);
            }

            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();

        return redirect()->back()->with('success', 'Banner berhasil diperbarui.');
    }

}

