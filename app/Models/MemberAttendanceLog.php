<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberAttendanceLog extends Model
{
    
    protected $table = 'member_attendance_logs';

    protected $fillable = [
        'user_id',
        'logged_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}