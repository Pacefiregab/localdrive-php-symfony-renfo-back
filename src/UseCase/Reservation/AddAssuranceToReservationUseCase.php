<?

namespace App\UseCase\Reservation;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Reservation\StatutReservationEnum;

class AddAssuranceToReservationUseCase
{
    public function __construct(private ReservationRepositoryInterface
    $repository) {}
    public function execute(int $reservationId): void
    {
        $reservation = $this->repository->findReservationById($reservationId);

        $reservation->addAssurance(true);
        
        $this->repository->save($reservation);
    }
}