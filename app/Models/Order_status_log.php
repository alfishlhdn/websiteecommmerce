<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_status_log extends Model
{
    protected $table = 'order_status_logs';

    protected $fillable = [
        'order_id',
        'status',
        'note',
        'changed_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
