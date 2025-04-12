<?

namespace App\UseCase\Reservation;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Reservation\StatutReservationEnum;

class RemoveAssuranceFromReservationUseCase
{
    public function __construct(private ReservationRepositoryInterface $repository) {}

    public function execute(int $reservationId): void
    {
        $reservation = $this->repository->findReservationById($reservationId);

        $reservation->removeAssurance();

        $this->repository->save($reservation);
    }
}