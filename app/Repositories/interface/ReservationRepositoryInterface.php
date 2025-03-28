<?php

namespace App\Repositories\interface;

use App\Models\Seance;

interface ReservationRepositoryInterface
{
    public function checkAvailableSieges(Seance $seance);
    public function createReservation(array $data);
    public function confirmReservation($reservationId);
    public function cancelReservation($reservationId);
}