<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store_Setiing extends Model
{
    use HasFactory;

    protected $table = 'store_settings';

    protected $fillable = [
        'store_name',
        'logo',
        'email',
        'phone',
        'address',
        'address_ingooglemaps'
    ];
}
