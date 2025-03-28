<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'capacite',
        'type',
    ];

    public function sieges()
    {
        return $this->hasMany(Siege::class);
    }
}
