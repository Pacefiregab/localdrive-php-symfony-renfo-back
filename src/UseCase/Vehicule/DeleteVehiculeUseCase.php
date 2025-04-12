<?

namespace App\UseCase\Vehicule;

use App\Entity\Vehicule\Port\VehiculeRepositoryInterface;

class DeleteVehiculeUseCase
{
    public function __construct(private VehiculeRepositoryInterface $repository) {}

    public function execute(int $vehicleId): void
    {
        $vehicle = $this->repository->findVehiculeById($vehicleId);

        if (!$vehicle) {
            throw new \InvalidArgumentException('Véhicule non trouvé.');
        }

        $this->repository->remove($vehicle);
    }
}