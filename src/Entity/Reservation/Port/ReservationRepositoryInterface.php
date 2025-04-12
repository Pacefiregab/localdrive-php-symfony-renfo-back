<?php

namespace App\Entity\Reservation\Port;

use App\Entity\Reservation\Reservation;
use App\Entity\User\User;

interface ReservationRepositoryInterface
{
    public function save(Reservation $reservation): void;

    public function findReservationById(int $id): ?Reservation;

    public function findReservationsByClient(User $clientId): array;
}