<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kurir;
use App\Models\Order_Item;
use App\Models\User_Addresses;
use App\Models\Payment_methods;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'kode_pesanan',
        'user_id',
        'user_address_id',
        'kurir_id',
        'payment_method_id',
        'subtotal',
        'shipping_cost',
        'total',
        'catatan',
        'payment_status',
        'shipping_status',
        'qris_source',
        'qris_image_path',
        'qris_payload',
        'proof_path',
        'proof_submitted_at',
        'confirmed_at',
        'cancelled_at',
        'return_to',
        'nomor_resi',
    ];

    // Relasi
    public function items() {
        return $this->hasMany(Order_Item::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(User_Addresses::class, 'user_address_id', 'id');
    }

    public function kurir() {
        return $this->belongsTo(Kurir::class);
    }

    public function paymentMethod() {
        return $this->belongsTo(Payment_methods::class);
    }
}