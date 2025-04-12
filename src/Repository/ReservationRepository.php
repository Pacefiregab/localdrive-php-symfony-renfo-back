<?php

namespace App\Repository;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\Reservation\Reservation;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ReservationRepository extends ServiceEntityRepository implements ReservationRepositoryInterface
{
    public function save(Reservation $reservation): void
    {
        $this->_em->persist($reservation);
        $this->_em->flush();
    }

    public function findReservationById(int $id): ?Reservation
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findReservationsByClient(User $clientId): array
    {
        return $this->findBy(['client' => $clientId]);
    }
}