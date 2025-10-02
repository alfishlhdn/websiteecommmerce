<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kurir extends Model
{
    use HasFactory;

    protected $table = 'kurirs';

    protected $fillable = [
        'name',
        'service_type',
        'service_code',
        'keterangan',
        'price',
    ];
    public function orders() { return $this->hasMany(Order::class, 'kurir_id'); }
}