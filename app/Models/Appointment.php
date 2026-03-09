<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded = [];

    // Relationship with accounts
    public function account()
    {
        return $this->hasOne(Account::class, 'client_id');
    }
}
