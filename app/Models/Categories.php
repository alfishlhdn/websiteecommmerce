<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nama_kategori',
        'icon',
        'slug',
        'deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            $kategori->slug = Str::slug($kategori->nama_kategori);
        });

        static::updating(function ($kategori) {
            $kategori->slug = Str::slug($kategori->nama_kategori);
        });
    }

}
