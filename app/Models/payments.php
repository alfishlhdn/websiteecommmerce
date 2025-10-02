<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Payment_methods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class payments extends Model
{
    protected $fillable = [
        'order_id','transaction_id','payment_type','status',
        'fraud_status','snap_token','snap_redirect_url',
        'va_numbers','pdf_url'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}