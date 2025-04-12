<?

namespace App\UseCase\Reservation;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Reservation\StatutReservationEnum;

class PayReservationUseCase
{
    public function __construct(private ReservationRepositoryInterface $repository) {}

    public function execute(int $reservationId, string $modePaiement): void
    {
        $reservation = $this->repository->findReservationById($reservationId);

        $reservation->pay($modePaiement);

        $this->repository->save($reservation);
    }
}