<?

namespace App\Application\UseCase\Vehicule;

use App\Entity\Vehicule\Vehicule;
use App\Entity\Vehicule\Port\VehiculeRepositoryInterface;

class CreateVehiculeUseCase
{
    public function __construct(private VehiculeRepositoryInterface $repository) {}

    public function execute(CreateVehiculeInput $input): void
    {

        $vehicule = new Vehicule($input->marque, $input->modele, $input->prixJournalier);

        $this->repository->save($vehicule);
    }
}