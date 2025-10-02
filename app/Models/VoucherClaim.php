<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherClaim extends Model
{
    use HasFactory;

    protected $table = 'voucher_claims';

    protected $fillable = ['voucher_id','user_id','claimed_at','used_at'];

}
