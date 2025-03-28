<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        $this->middleware('auth:api'); // Assure-toi que l'utilisateur est authentifié avant d'accéder aux actions
    }

    

    // Pour créer une réservation
    public function store(Request $request)
    {
        $userId = Auth::id(); 

        $data = $request->all(); 
        $data['user_id'] = $userId; 

        return $this->reservationService->createReservation($data);

        return response()->json([
            'message' => 'تم الحجز بنجاح! يمكنك الآن الدفع.',
            'reservationId' => $reservation->id,
        ]);
    }

    public function update(Request $request  , $id)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
      //  return response()->json($data);
        return $this->reservationService->updateResevation($data , $id);
    }


    // Pour confirmer une réservation après paiement
    public function confirm($id)
    {
        $userId = Auth::id();  // Vérifier l'utilisateur authentifié
        return $this->reservationService->confirmReservation($id, $userId); // Confirmer la réservation
    }

    // Pour annuler une réservation si le paiement n'a pas été effectué dans les 15 minutes
    public function cancel($id)
    {
        $userId = Auth::id();  
        return $this->reservationService->cancelReservation($id, $userId); 
    }

}