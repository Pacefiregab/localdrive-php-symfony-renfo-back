<?

namespace App\Entity\Reservation;

enum StatutReservationEnum: string
{
    case CART = 'cart';                 // Panier en cours, modifiable
    case PENDING_PAYMENT = 'pending';  // Paiement sélectionné mais pas encore effectué
    case PAID = 'paid';                // Réservation payée
    case CANCELLED = 'cancelled';      // Réservation annulée
}