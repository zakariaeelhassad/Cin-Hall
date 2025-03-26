<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Seance;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $Reservations = $this->reservationService->all();

        return response()->json([
            'status' => 'success',
            'data' => $Reservations
        ], 200);
    }

    public function store(Request $request , $seance_id)
    {
        $data = $request->validate([
            'seats' => 'required|array',
            'seats.*' => 'required|integer|distinct',
        ]);


        $seance = Seance::find($seance_id);
        if (!$seance) {
            return response()->json([
                'message' => 'Seance non trouvée.'
            ], 404);
        }
        $expiration_time = now()->addMinutes(15);
        
        $data['expiration_time'] = $expiration_time;
        $data['seance_id'] = $seance_id ;
        $data['user_id'] = auth('api')->id();
        $data['status'] = 'pending'; 
        $Reservation = $this->reservationService->create($data);
        

        if (!$Reservation instanceof Reservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Salle'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $Reservation
        ], 201);
    }

    public function show(int $id)
    {
        $Reservation = $this->reservationService->find($id);

        if (!$Reservation instanceof Reservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Salle not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $Reservation
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'capacite' => 'required|integer',
            'type' => 'in:Normale,VIP'
        ]);

        $result = $this->reservationService->update($data, $id);

        if ($result <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Salle'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Salle updated successfully'
        ],200);
    }

    public function destroy(int $id)
    {
        $result = $this->reservationService->delete($id);

        if (!is_bool($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Salle'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Salle deleted successfully'
        ], 200);
    }

    public function confirm(Reservation $reservation)
    {
        // Vérifier si la réservation appartient à l'utilisateur connecté
        $this->authorize('update', $reservation);

        // Marquer la réservation comme confirmée
        $reservation->confirm();

        return response()->json([
            'message' => 'Réservation confirmée avec succès.',
            'reservation' => $reservation
        ]);
    }

    /**
     * Annule une réservation.
     */
    public function cancel(Reservation $reservation)
    {
        // Vérifier si la réservation appartient à l'utilisateur connecté
        $this->authorize('update', $reservation);

        // Marquer la réservation comme annulée
        $reservation->cancel();

        return response()->json([
            'message' => 'Réservation annulée avec succès.',
            'reservation' => $reservation
        ]);
    }

    /**
     * Vérifie et annule les réservations expirées.
     */
    public function checkExpired()
    {
        // Récupérer toutes les réservations expirées
        $expiredReservations = Reservation::where('expiration_time', '<', now())
                                           ->where('status', 'pending')
                                           ->get();

        // Annuler les réservations expirées
        foreach ($expiredReservations as $reservation) {
            $reservation->cancel();
        }

        return response()->json([
            'message' => count($expiredReservations) . ' réservations expirées annulées.'
        ]);
    }

}
