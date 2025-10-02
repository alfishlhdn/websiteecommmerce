<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_path',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
