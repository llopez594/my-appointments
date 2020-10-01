<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelledAppointments extends Model
{
    public function cancelled_by()
    {   // belongTo Cancellation N - 1 User hasMany
        return $this->belongsTo(User::class);
    }
}
