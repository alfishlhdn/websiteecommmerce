<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor_Log extends Model
{
    use HasFactory;

    protected $table = 'visitor_logs';
    // public $timestamps = true;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'halaman',
    ];
}
