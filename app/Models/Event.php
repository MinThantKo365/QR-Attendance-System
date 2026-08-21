<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'event_start',
        'event_end',
        'location',
        'status',
    ];

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
