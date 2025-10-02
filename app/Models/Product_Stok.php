<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Stok extends Model
{
    use HasFactory;

    protected $table = 'product_stocks';

    protected $fillable = [
        'product_id',
        'tipe',
        'jumlah',
        'keterangan',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
