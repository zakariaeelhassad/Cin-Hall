<?php

namespace App\Services;


use App\Repositories\interface\RepositoryInterface;
use App\Repositories\ReservationRepository;
use App\Repositories\SeanceRepository;
use App\Repositories\SiegeRepository;

use function PHPUnit\Framework\isEmpty;

class ReservationService
{
    protected $reservationRepository;
    private $seanceRepository;
    private $siegeRepository;

    public function __construct(ReservationRepository $reservationRepository , SeanceRepository $seanceRepository , SiegeRepository $siegeRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->seanceRepository = $seanceRepository;
        $this->siegeRepository = $siegeRepository;
    }

    public function createReservation(array $data)
    {
        $seance = $this->seanceRepository->find($data['seance_id']);
        $siege = $this->siegeRepository->find($data['siege_id']);
        
        if (empty($seance)) {
            return response()->json(['error' => 'Il n\'y a pas de séance avec cet ID'], 403);
        }
        
        if (empty($siege)) {
            return response()->json(['error' => 'Il n\'y a pas de siège avec cet ID'], 403);
        }

        // Vérifier l'existence du siège dans la salle où passe le film
        if ($seance->salle_id != $siege->salle_id) {
            return response()->json(['error' => 'Ce siège n\'existe pas dans la salle où déroule la séance.'], 403);
        }

        // Vérifier la disponibilité des sièges via le repository
        $availableSieges = $this->reservationRepository->checkAvailableSieges($seance);

        // Logique pour la réservation VIP ou normale
        if ($seance->isVIP()) {
            if ($availableSieges->count() < 2) {
                return response()->json(['message' => 'Il n\'y a pas de sièges doubles disponibles pour cette séance VIP.'], 400);
            }

            // Réserver deux sièges et mettre à jour les réservations
            $siege1 = $availableSieges->first();
            $siege2 = $availableSieges->skip(1)->first();

            $this->reservationRepository->createReservation([
                'user_id' => $data['user_id'],
                'siege_id' => $siege1->id,
                'seance_id' => $seance->id,
                'status' => 'pending',
            ]);

            $this->reservationRepository->createReservation([
                'user_id' => $data['user_id'],
                'siege_id' => $siege2->id,
                'seance_id' => $seance->id,
                'status' => 'pending',
            ]);

            return response()->json(['message' => 'Réservation modifiée avec succès, paiement dans les 15 minutes.'], 200);
        }

        if (!$availableSieges->contains('id', $siege->id)) {
            return response()->json(['message' => 'Aucun siège disponible pour cette séance. Vous pourriez choisir parmi ces sièges encore disponibles.', 'contains' => $availableSieges], 400);
        }

        $this->reservationRepository->createReservation([
            'user_id' => $data['user_id'],
            'siege_id' => $siege->id,
            'seance_id' => $seance->id,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Réservation ajoutée avec succès, paiement dans les 15 minutes.'], 200);
    }


    public function updateResevation($data, $reservationId)
    {
        $reservation = $this->reservationRepository->getReservation($reservationId);
        $seance = $this->seanceRepository->find($data['seance_id']);
        $siege = $this->siegeRepository->find($data['siege_id']);

        if ($reservation->user_id != $data['user_id']) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette réservation.'], 403);
        }

        if(is_null($seance) ){
            return response()->json(['error'=>'il y a pas de seance avec ce id'], 403) ;
        }
        if( is_null($siege)){
            return response()->json(['error'=>'il y a pas de seige   avec ce id'], 403) ;
        }

        if($seance->salle_id != $siege->salle_id){
            return response()->json(['error'=>'ce seige n est existe pas dans la salle ou deroule la seance  '], 403) ;
        }

        $availableSieges = $this->reservationRepository->checkAvailableSieges($seance);

        if ($seance->isVIP()) {
            if ($availableSieges->count() < 2) {
                return response()->json(['message' => 'Il n y a pas de sièges doubles disponibles pour cette séance VIP.'], 400);
            }

            $siege1 = $availableSieges->first();
            $siege2 = $availableSieges->skip(1)->first();

            $this->reservationRepository->updateReservation($reservation, [
                'user_id' => $data['user_id'],
                'siege_id' => $siege1->id,
                'seance_id' => $seance->id,
                'status' => 'pending',
            ]);

            $this->reservationRepository->updateReservation($reservation ,[
                'user_id' => $data['user_id'],
                'siege_id' => $siege2->id,
                'seance_id' => $seance->id,
                'status' => 'pending',
            ]);

            return response()->json(['message' => 'Réservation modifiée avec succès, paiement dans les 15 minutes.'], 200);
        }

        if (!$availableSieges->contains('id', $siege->id)) {
            return response()->json(['message' => 'Aucun siège disponible pour cette séance.'  , 'contains'=> $availableSieges->contains($siege) ], 400);
        }

        $this->reservationRepository->updateReservation($reservation, [
            'user_id' => $data['user_id'],
            'siege_id' => $siege->id,
            'seance_id' => $seance->id,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Réservation modifiée avec succès, paiement dans les 15 minutes.'], 200);
    }

    public function confirmReservation($reservationId)
    {
        return $this->reservationRepository->confirmReservation($reservationId);
        if (!$reservation) {
            return response()->json(['error' => 'حجز غير موجود'], 404);
        }

        $reservation->status = 'confirmed';
        $reservation->save();

        return response()->json(['message' => 'تم تأكيد الحجز بنجاح.']);
    }

    public function cancelReservation($reservationId)
    {
        return $this->reservationRepository->cancelReservation($reservationId);
    }
    

    


}