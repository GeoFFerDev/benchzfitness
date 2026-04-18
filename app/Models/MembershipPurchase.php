<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPurchase extends Model
{
     protected $fillable = [
        'user_id',
        'membership_tier_id',
        'price',
        'duration',
        'purchased_at',
        'expires_at',
    ];
}
