<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siege extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id',
        'numero',
        'type',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
