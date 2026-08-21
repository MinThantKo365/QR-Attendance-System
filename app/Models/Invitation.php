<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'invite_id',
        'event_id',
        'name',
        'email',
        'phone',
        'status',
        'sent_at',
        'expires_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

     public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
