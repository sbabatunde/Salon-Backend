<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{

    protected $fillable = [
        'name',
        'review',
        'image_url',
        'rating',
        'token',
        'submitted',
        'client_id',
        'token_created_at'
    ];

    protected $casts = [
        'token_created_at' => 'datetime',
    ];

    // Relationship to client/appointment (adjust as needed)
    public function client()
    {
        return $this->belongsTo(Appointment::class); // Update to your actual client model
    }

    // Add this to your Testimonial model
    public function isTokenExpired(): bool
    {
        return $this->token_created_at->diffInMinutes(now()) > 30;
    }
}
