<?

namespace App\Application\UseCase\Vehicule;

class CreateVehiculeInput
{
    public function __construct(
        public string $marque,
        public string $modele,
        public float $prixJournalier
    ) {}
}