<?php

namespace App\Entity\Vehicule;

use App\Entity\Reservation\ReservationItem;
use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicule:read', 'reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:read', 'reservation:read'])]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:read', 'reservation:read'])]
    private ?string $modele = null;

    #[ORM\Column]
    #[Groups(['vehicule:read', 'reservation:read'])]
    private ?float $prix_journalier = null;

    /**
     * @var Collection<int, ReservationItem>
     */
    #[ORM\OneToMany(targetEntity: ReservationItem::class, mappedBy: 'vehicule')]
    private Collection $reservationItems;

    public function __construct($marque, $modele, $prix_journalier)
    {
        $this->checkPrixJournalier($prix_journalier);
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prix_journalier = $prix_journalier;
        $this->reservationItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getPrixJournalier(): ?float
    {
        return $this->prix_journalier;
    }

    public function setPrixJournalier(float $prix_journalier): static
    {
        $this->prix_journalier = $prix_journalier;

        return $this;
    }

    /**
     * @return Collection<int, ReservationItem>
     */
    public function getReservationItems(): Collection
    {
        return $this->reservationItems;
    }

    public function addReservationItem(ReservationItem $reservationItem): static
    {
        if (!$this->reservationItems->contains($reservationItem)) {
            $this->reservationItems->add($reservationItem);
            $reservationItem->setVehicule($this);
        }

        return $this;
    }

    public function removeReservationItem(ReservationItem $reservationItem): static
    {
        if ($this->reservationItems->removeElement($reservationItem)) {
            // set the owning side to null (unless already changed)
            if ($reservationItem->getVehicule() === $this) {
                $reservationItem->setVehicule(null);
            }
        }

        return $this;
    }

    public function update(string $brand, string $model, float $price): void
    {
        if (empty($brand)) {
            throw new \InvalidArgumentException('La marque ne peut pas être vide.');
        }
        if (empty($model)) {
            throw new \InvalidArgumentException('Le modèle ne peut pas être vide.');
        }
        if (empty($price)) {
            throw new \InvalidArgumentException('Le prix ne peut pas être vide.');
        }

        if ($price <= 0) {
            throw new \InvalidArgumentException('Le tarif doit être supérieur à 0.');
        }

        $this->checkPrixJournalier($price);
        $this->marque = $brand;
        $this->modele = $model;
        $this->prix_journalier = $price;
    }

    private function checkPrixJournalier($prixJournalier): void
    {
        if ($prixJournalier <= 0) {
            throw new \InvalidArgumentException('Le prix journalier doit être supérieur à 0.');
        }
    }
}