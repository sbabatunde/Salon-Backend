<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'image',
        'bio',
        'awards',
        'instagram',
        'email',
        'specializations',
        'is_active',
        'display_order'
    ];

    protected $casts = [
        'awards' => 'array',
        'specializations' => 'array',
        'is_active' => 'boolean'
    ];

    // Scope for active stylists
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }

    // Get specialization badge color
    public function getSpecializationColor($spec)
    {
        $colors = [
            'Bridal' => 'pink',
            'Color' => 'purple',
            'Extension' => 'blue',
            'Men\'s' => 'green',
            'Texture' => 'orange',
            'Editorial' => 'red',
        ];

        return $colors[$spec] ?? 'yellow';
    }
}
