<?php

namespace App\UseCase\Reservation;

class AddVehiculeToReservationInput
{
    public function __construct(
        public int $reservationId,
        public int $vehiculeId
    ) {}
}