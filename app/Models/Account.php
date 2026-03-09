<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'client_id',
        'amount_paid',
        'service_cost',
        'material_cost',
        'other_cost',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'service_cost' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
    ];

    // Relationship with appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'client_id');
    }
}