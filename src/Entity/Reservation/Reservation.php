<?php

namespace App\Entity\Reservation;

use App\Entity\User\User;
use App\Entity\Vehicule\Vehicule;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{

    final ?float $PRIX_ASSURANCE = 20;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?int $id = null;

    /**
     * @var Collection<int, ReservationItem>
     */
    #[ORM\OneToMany(targetEntity: ReservationItem::class, mappedBy: 'reservation')]
    #[Groups(['reservation:read'])]
    private Collection $reservationItems;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(type: 'string', enumType: StatutReservationEnum::class)]
    #[Groups(['reservation:read'])]
    private ?StatutReservationEnum $statut = null;

    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?bool $assurance = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups(['reservation:read'])]
    private ?User $client = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['reservation:read'])]
    private ?float $totalPrice = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['reservation:read'])]
    private ?string $modePaiement = null;
    public function __construct($date_debut, $date_fin, ?User $client = null)
    {

        $now = new \DateTimeImmutable();
        if ($date_debut <= $now || $date_fin <= $date_debut) {
            throw new \InvalidArgumentException('Dates invalides.');
        }

        $this->client = $client;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->assurance = false;
        $this->statut = StatutReservationEnum::CART;
        $this->date_creation = new \DateTimeImmutable();
        $this->reservationItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?StatutReservationEnum
    {
        return $this->statut;
    }

    private function setStatut(StatutReservationEnum $statut): static
    {
        $this->statut = $statut;

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
            $reservationItem->setReservation($this);
        }

        return $this;
    }

    public function removeReservationItem(ReservationItem $reservationItem): static
    {
        if ($this->reservationItems->removeElement($reservationItem)) {
            // set the owning side to null (unless already changed)
            if ($reservationItem->getReservation() === $this) {
                $reservationItem->setReservation(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function isAssurance(): ?bool
    {
        return $this->assurance;
    }

    private function setAssurance(bool $assurance): static
    {
        $this->assurance = $assurance;

        return $this;
    }
    public function getClient(): ?User
    {
        return $this->client;
    }

    private function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function addItem(Vehicule $vehicule): static
    {
        if ($this->getStatut() !== StatutReservationEnum::CART) {
            throw new \DomainException('Réservation introuvable ou non modifiable.');
        }

        if (!$vehicule) {
            throw new \DomainException('Véhicule introuvable.');
        }

        $item = new ReservationItem();
        $item->setVehicule($vehicule)->setReservation($this);


        if (!$this->reservationItems->contains($item)) {
            $this->reservationItems[] = $item;
            $item->setReservation($this);
        }

        return $this;
    }

    public function removeItemById(int $itemId): static
    {
        if ($this->getStatut() !== StatutReservationEnum::CART) {
            throw new \DomainException('Impossible de modifier la réservation.');
        }

        foreach ($this->reservationItems as $item) {
            if ($item->getId() === $itemId) {
                $this->removeReservationItem($item);
                break;
            }
        }

        return $this;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function deductTotalPrice(): float
    {
        $total = 0.;
        foreach ($this->reservationItems as $item) {
            $total += $item->getVehicule()->getPrixJournalier() * $this->getDaysBetween($this->date_debut, $this->date_fin);
        }
        if ($this->assurance) {
            $total += $this->PRIX_ASSURANCE;
        }
        return $total;
    }

    private function getDaysBetween(\DateTimeInterface $start, \DateTimeInterface $end): int
    {
        return (int) $start->diff($end)->format('%a');
    }

    public function addAssurance()
    {

        if ($this->getStatut() !== StatutReservationEnum::CART) {
            throw new \DomainException('Réservation non modifiable.');
        }

        if ($this->isAssurance()) {
            throw new \DomainException('Assurance déjà ajoutée.');
        }

        $this->setAssurance(true);
    }

    public function removeAssurance()
    {
        if ($this->getStatut() !== StatutReservationEnum::CART) {
            throw new \DomainException('Réservation non modifiable.');
        }

        if (!$this->isAssurance()) {
            throw new \DomainException('Pas d\'assurance a retirer.');
        }

        $this->setAssurance(false);
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function pay($modepaiement)
    {
        if ($this->getStatut() !== StatutReservationEnum::CART) {
            throw new \DomainException('Réservation non modifiable.');
        }

        if ($modepaiement !== 'CB' || $modepaiement !== 'PayPal') {
            throw new \DomainException('Mode de paiement non valide.');
        }

        if ($this->getTotalPrice() <= 0) {
            throw new \DomainException('Montant total invalide.');
        }
        $this->setTotalPrice($this->deductTotalPrice());
        $this->setModePaiement($modepaiement);

        $this->setStatut(StatutReservationEnum::PAID);
    }
}