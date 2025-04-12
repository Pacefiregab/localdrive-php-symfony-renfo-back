<?

namespace App\UseCase\Reservation;

use App\Entity\Reservation\Reservation;
use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Reservation\StatutReservationEnum;
use App\Entity\User\User;

class CreateReservationUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    public function execute(CreateReservationInput $input, User $client): void
    {
        $reservation = new Reservation($input->dateDebut, $input->dateFin, $client);

        $this->reservationRepository->save($reservation);
    }
}