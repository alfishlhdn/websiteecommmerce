<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Review;
use App\Models\Discounts;
use App\Models\Categories;
use App\Models\Order_Item;
use App\Models\Product_Specification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'brand_id',
        'nama_produk',
        'slug',
        'deskirpsi',
        'harga',
        'stok',
        'foto',
        'berat',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class)->withDefault([
            'nama_kategori' => '- Kategori Tidak Ada (Telah Dihapus)'
        ]);
    }

    public function discounts()
    {
        return $this->hasMany(Discounts::class, 'product_id');
    }

    // Product.php
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'name' => '- Brand Tidak Ada (Telah Dihapus)'
        ]);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(Order_Item::class);
    }


    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function specifications()
    {
        return $this->hasMany(Product_Specification::class, 'product_id');
    }

    public function priceList()
    {
        return $this->hasOne(PriceList::class)->whereNull('client_id');
    }

}
