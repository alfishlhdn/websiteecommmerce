<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $reviews = Review::with(['user', 'product'])
            ->when($search, function($query) use ($search) {
                $query->whereHas('product', function($q) use ($search) {
                    $q->where('nama_produk', 'like', "%$search%");
                })->orWhereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('view-menu-dashboard.ulasan', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return redirect()->back()->with('error', 'Ulasan tidak ditemukan.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->status = $request->status;
        $review->save();

        return back()->with('success', 'Status ulasan diperbarui.');
    }
}

