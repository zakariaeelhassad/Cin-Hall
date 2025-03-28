<?php
namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Siege;
use App\Models\Seance;
use App\Models\Film;
use App\Repositories\interface\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReservationRepository implements ReservationRepositoryInterface
{
    // Vérifie les sièges disponibles pour une séance donnée
    public function checkAvailableSieges(Seance $seance)
    {
        return DB::table('sieges')
            ->whereNotIn('id', function ($query) use ($seance) {
                $query->select('siege_id')
                    ->from('reservations')
                    ->where('seance_id', $seance->id)
                    ->whereIn('status', ['reserved', 'pending']); // Exclure les sièges réservés ou en attente
            })
            ->get();
    }
    //verifier est ce que l siege n° x  est disponilbe
    public function checkAvailableSiege(Seance $seance, Siege $siege)
    {   //on verfier est ce qu il y a une reservation avec le seige choisi
       $reservation = DB::table('reservations')
            ->where('siege_id', $siege->id)
            ->where('seance_id', $seance->id)
            ->whereIn('status', ['reserved', 'pending']) // Vérifier si ce siège est déjà pris
            ->exists(); // Retourne true si une réservation existe, false sinon
       if($reservation){
           return false;
       } else {
           return true;
       }
    }
    public function checkSeigeExistInSalleSeance(Seance $seance, Siege $siege){
        $salle_id=$seance->salle_id ;
        return DB::table('sieges')
            ->where('salle_id' , $salle_id) ;
    }

    public function confirmReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Vérifier que la réservation est en attente
        if ($reservation->status == 'pending') {
            $reservation->status = 'reserved';
            $reservation->save();

            return response()->json(['message' => 'Réservation confirmée.'], 200);
        }

        return response()->json(['message' => 'Cette réservation ne peut pas être confirmée.'], 400);
    }

    // Annuler une réservation si elle n'est pas payée dans les 15 minutes
    public function cancelReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Vérifier que la réservation est en attente
        if ($reservation->status == 'pending') {
            // Annuler la réservation
            $reservation->status = 'cancelled';
            $reservation->save();

            return response()->json(['message' => 'Réservation annulée.'], 200);
        }

        return response()->json(['message' => 'Cette réservation ne peut pas être annulée.'], 400);
    }

    public function updateReservation($reservation, $data)
    {
        // Mise à jour d'une réservation
        $reservation->update([
            'user_id' => $data['user_id'],
            'siege_id' => $data['siege_id'],
            'seance_id' => $data['seance_id'],
            'status' => 'pending',
        ]);

        return $reservation;
    }

    public function getReservation($reservationId){
       return  Reservation::find($reservationId);
//        return DB::table('reservations')
//                 ->where('id', $reservationId)
//                 ->first();

    }
    public function createReservation(array $data)
    {
        return Reservation::create($data);
    }
}