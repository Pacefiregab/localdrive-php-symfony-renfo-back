<?

namespace App\UseCase\Reservation;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Vehicule\Port\VehiculeRepositoryInterface;
use App\Entity\Reservation\ReservationItem;
use App\Entity\Reservation\StatutReservationEnum;

class AddVehiculeToReservationUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private VehiculeRepositoryInterface $vehiculeRepository
    ) {}

    public function execute(AddVehiculeToReservationInput $input): void
    {
        $reservation = $this->reservationRepository->findReservationById($input->reservationId);
        $vehicule = $this->vehiculeRepository->findVehiculeById($input->vehiculeId);

        $reservation->addItem($vehicule);

        $this->reservationRepository->save($reservation);
    }
}