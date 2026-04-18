<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlans extends Model
{
     protected $fillable = [
        'name',
        'tag',
        'duration',
        'price',
        'description',
        'image_path',
    ];
}
