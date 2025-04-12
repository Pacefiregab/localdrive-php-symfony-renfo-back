<?

namespace App\UseCase\Vehicule;

class UpdateVehiculeInput
{
    public function __construct(
        public int $id,
        public string $brand,
        public string $model,
        public float $dailyPrice
    ) {}
}