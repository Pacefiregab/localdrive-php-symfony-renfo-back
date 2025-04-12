<?php

namespace App\UseCase\Reservation;

class CreateReservationInput
{
    public function __construct(
        public \DateTimeInterface $dateDebut,
        public \DateTimeInterface $dateFin
    ) {}
}