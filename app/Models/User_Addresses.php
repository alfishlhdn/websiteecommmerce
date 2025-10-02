<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User_Addresses extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'penerima',
        'telepon',
        'alamat_lengkap',
        'kecamatan',
        'kota',
        'provinsi',
        'kelurahan',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
