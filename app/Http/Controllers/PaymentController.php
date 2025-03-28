<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Seance;
use App\Models\Siege;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use App\Models\Reservation;

class PaymentController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        $this->middleware('auth:api')->except(['success', 'cancel']); // Exclure success et cancel
    }

    // Méthode pour créer la session de paiement
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non authentifié.'], 401);
            }

            // Récupérer la réservation depuis la base de données
            $reservation = Reservation::find($request->reservation_id);
            if (!$reservation) {
                return response()->json(['error' => 'Réservation introuvable.'], 404);
            }

            $seance = Seance::find($reservation->seance_id);
            $film = Film::find($seance->film_id);
            $siege = Siege::find($reservation->siege_id);

            $priceInCents = intval($reservation->prix * 100);

            // Créer une session de paiement Stripe
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $film->title,
                                'description' => "Séance de {$seance->type_seance} pour le film {$film->title} - Siège numéro {$siege->numero} à {$seance->start_time}",
                            ],
                            'unit_amount' => $priceInCents,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['reservation_id' => $reservation->id]),
                'metadata' => [
                    'reservation_id' => $reservation->id,
                ],
                'cancel_url' => route('payment.cancel'),
                'client_reference_id' => $user->id,
            ]);

            return response()->json(['url' => $session->url], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Méthode pour gérer le succès du paiement
    public function success(Request $request)
    {
        try {
            $reservation = Reservation::find($request->reservation_id);
            if (!$reservation) {
                return response()->json(['error' => 'Réservation introuvable.'], 404);
            }

            $reservation->update(['status' => 'accepted']);

            return response()->json(['message' => 'Réservation confirmée avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    // Méthode pour gérer l'annulation du paiement
    public function cancel(Request $request)
    {
        return response()->json(['message' => 'Le paiement a été annulé.'], 200);
    }

    // Méthode pour gérer les webhooks de Stripe (confirmer le paiement)
    public function handleWebhook(Request $request)
    {
        \log::info('Webhook Stripe reçu', ['payload' => $request->all()]);

        try {
            $event = $request->all();

            if ($event['type'] === 'checkout.session.completed') {
                $session = $event['data']['object'];
                $reservation = Reservation::find($session['metadata']['reservation_id']);

                if ($reservation) {
                    $reservation->update(['status' => 'reserved']);
                    return response()->json(['message' => 'Réservation mise à jour.'], 200);
                }
            }

            return response()->json(['message' => 'Événement ignoré.'], 200);
        } catch (\Exception $e) {
            \log::error('Erreur Webhook Stripe', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur Webhook'], 500);
        }
    }
}
