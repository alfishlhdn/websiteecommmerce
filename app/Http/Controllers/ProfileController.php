<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User_Addresses;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ambil data user login
        return view('view-menu-dashboard.profile', compact('user'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function indexprofillayout()
    {
        $user = Auth::user(); // Ambil data user login

        $addresses = User_Addresses::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $orders = Order::with('items','paymentMethod')->where('user_id', auth()->id())
            ->latest()
            ->paginate(5); // ✅ cukup ini, tanpa get()

        return view('view-menu-layout.profile', compact('user', 'addresses', 'orders'));
    }


        // update data profil
    public function updateprofillayout(Request $request)
    {
        $user = Auth::user();

        // validasi
        $data = $request->validate([
            // gabungkan first_name + last_name jadi name
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string'],
        ]);

        // gabungkan nama
        $first = trim($data['first_name'] ?? '');
        $last = trim($data['last_name'] ?? '');
        $name = $first . ($last !== '' ? ' ' . $last : '');

        if ($name === '') {
            // fallback ke email sebelum menyimpan nama kosong
            $name = $user->name ?? $user->email;
        }

        $user->name = $name;
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? $user->phone;


        // hanya update password jika user mengisi field password
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

        // store new address
    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'label' => 'required',
            'telepon' => 'nullable|string|max:30',
            'alamat_lengkap' => 'required|string|max:1000',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
        ]);

        $data['user_id'] = $user->id;

        User_Addresses::create($data);

        return redirect()->route('profil')->with('success','Alamat berhasil ditambahkan.');
    }

    // update existing address
    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $addr = User_Addresses::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $data = $request->validate([
            'label' => 'required',
            'telepon' => 'nullable|string|max:30',
            'alamat_lengkap' => 'required|string|max:1000',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255'
        ]);

        $addr->update($data);

        return redirect()->route('profil')->with('success','Alamat berhasil diperbarui.');
    }

    // delete address
    public function destroyAddress($id)
    {
        $user = Auth::user();
        $addr = User_Addresses::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $addr->delete();

        return redirect()->route('profil')->with('success','Alamat berhasil dihapus.');
    }

    public function orderDetail($id)
    {
        $order = Order::with(['items.product'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'kode_pesanan'     => $order->kode_pesanan,
                'total'            => $order->total,
                'payment_status'   => $order->payment_status,
                'shipping_status'  => $order->shipping_status, // ✅ tambahin ini
                'nomor_resi'       => $order->nomor_resi,     // ✅ tambahin ini
                'kurir'            => $order->kurir ?? '-',   // ✅ kalau kurir disimpan di field order
                'items' => $order->items->map(function ($item) {
                    return [
                        'nama_produk' => $item->nama_produk?? '-',
                        'jumlah'      => $item->jumlah,
                        'subtotal'    => $item->subtotal,
                    ];
                })->toArray(),
            ]
        ]);
    }

    public function confirmOrder(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($order->payment_status === 'paid' && $order->shipping_status === 'shipped') {
            $order->shipping_status = 'delivered';
            $order->save();

            return response()->json(['success' => true, 'message' => 'Pesanan berhasil dikonfirmasi sebagai diterima.']);
        }

        return response()->json(['success' => false, 'message' => 'Pesanan tidak valid untuk konfirmasi.']);
    }


    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::where('user_id', Auth::id())
            ->findOrFail($request->order_id);

        if (in_array($order->payment_status, ['pending', 'waiting_confirmation'])) {
            // ✅ kembalikan stok produk
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->jumlah);
                }
            }

            // ✅ update status order
            $order->update([
                'payment_status'  => 'cancelled',
                'shipping_status' => 'cancelled',
                'cancelled_at'    => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan tidak bisa dibatalkan'
        ]);
    }

}
