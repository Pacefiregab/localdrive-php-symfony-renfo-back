<?

namespace App\UseCase\Vehicule;

use App\Entity\Vehicule\Port\VehiculeRepositoryInterface;

class UpdateVehiculeUseCase
{
    public function __construct(private VehiculeRepositoryInterface $repository) {}

    public function execute(UpdateVehiculeInput $input): void
    {
        $vehicle = $this->repository->findVehiculeById($input->id);

      
        $vehicle->update($input->brand, $input->model, $input->dailyPrice);

        $this->repository->save($vehicle);
    }
}