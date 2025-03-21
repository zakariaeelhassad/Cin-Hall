<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'titre',
        'description',
        'image',
        'durée',
        'langue',
        'age_min',
        'bande_annonce',
        'genre',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
