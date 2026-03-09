<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SignatureLook extends Model
{
    // app/Models/SignatureLook.php
    use HasFactory;

    protected $fillable = ['title', 'tag', 'image', 'status'];
}
