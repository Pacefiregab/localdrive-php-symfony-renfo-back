<?

namespace App\Entity\Vehicule\Port;

use App\Entity\Vehicule\Vehicule;

interface VehiculeRepositoryInterface
{
    public function save(Vehicule $vehicle): void;
    public function findVehiculeById(int $id): ?Vehicule;
    public function remove(Vehicule $vehicle): void;
    public function findAll(): array;
}