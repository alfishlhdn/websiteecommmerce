<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discounts extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'type',
        'discount_type',
        'value',
        'product_id',
        'user_id',
        'expired_at',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}