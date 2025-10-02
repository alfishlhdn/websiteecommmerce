<?php

namespace App\Models;

use App\Models\payments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment_methods extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'qris_image_path',
        'qris_payload',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}