<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipStatus extends Model
{
    protected $fillable = [
        'user_id',
        'planType',
        'expiry_date',
        'status',
        'start_date',
    ];
}
