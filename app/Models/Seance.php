<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $table = 'seance';

    protected $fillable = [
        'salle_id',
        'film_id',
        'date_heure',
        'type',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function isVIP()
    {
        return $this->type === 'VIP'; // تأكد أن لديك عمود `type` في جدول `seances`
    }
}
