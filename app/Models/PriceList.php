<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'client_id',
        'price',
        'min_qty',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
