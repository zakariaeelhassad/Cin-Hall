<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['seats', 'expiration_time', 'user_id', 'seance_id' , 'status'];

    protected $casts = [
        'seats' => 'array', 
        'expiration_time' => 'datetime', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(Seance::class);
    }

    public function isExpired()
    {
        return $this->expiration_time < now();
    }

    public function confirm()
    {
        $this->status = 'confirmed';
        $this->save();
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    public function markAsPending()
    {
        $this->status = 'pending';
        $this->save();
    }
}
