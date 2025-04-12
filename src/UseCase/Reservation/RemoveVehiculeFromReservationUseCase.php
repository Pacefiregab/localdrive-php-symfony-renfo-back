<?

namespace App\UseCase\Reservation;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Reservation\StatutReservationEnum;

class RemoveVehiculeFromReservationUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $repository
    ) {}

    public function execute(int $reservationId, int $itemId): void
    {
        $reservation = $this->repository->findReservationById($reservationId);
        
        $reservation->removeItemById($itemId); 
        
        $this->repository->save($reservation);
    }
}