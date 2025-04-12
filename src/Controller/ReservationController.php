<?php

namespace App\Controller;

use App\Entity\Reservation\Port\ReservationRepositoryInterface;
use App\Entity\User\User;
use App\UseCase\Reservation\AddAssuranceToReservationUseCase;
use App\UseCase\Reservation\PayReservationUseCase;
use App\UseCase\Reservation\RemoveAssuranceFromReservationUseCase;
use App\UseCase\Reservation\RemoveVehiculeFromReservationUseCase;
use App\UseCase\Reservation\AddVehiculeToReservationInput;
use App\UseCase\Reservation\AddVehiculeToReservationUseCase;
use App\UseCase\Reservation\CreateReservationInput;
use App\UseCase\Reservation\CreateReservationUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/reservations')]
#[IsGranted('ROLE_CLIENT')]
class ReservationController extends AbstractController
{

    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private CreateReservationUseCase $createReservationUseCase,
        private AddVehiculeToReservationUseCase $addVehiculeToReservationUseCase,
        private RemoveVehiculeFromReservationUseCase $removeVehiculeFromReservationUseCase,
        private AddAssuranceToReservationUseCase $addAssuranceToReservationUseCase,
        private RemoveAssuranceFromReservationUseCase $removeAssuranceFromReservationUseCase,
        private PayReservationUseCase $payReservationUseCase
    ) {}


    #[Route('', name: 'reservation_create', methods: ['POST'])]
    public function create(
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        try {
            $input = new CreateReservationInput(
                new \DateTimeImmutable($data['dateDebut'] ?? 'now'),
                new \DateTimeImmutable($data['dateFin'] ?? 'now')
            );

            /** @var User $client */
            $client = $this->getUser();
            $this->createReservationUseCase->execute($input, $client);

            return $this->json(['success' => true], 201);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}/vehicules', name: 'reservation_add_vehicule', methods: ['POST'])]
    public function addVehicule(
        int $id,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        try {
            $input = new AddVehiculeToReservationInput(
                $id,
                (int)($data['vehiculeId'] ?? 0)
            );

            $this->addVehiculeToReservationUseCase->execute($input);

            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}/vehicules/{itemId}', name: 'reservation_remove_vehicule', methods: ['DELETE'])]
    public function removeVehicule(
        int $id,
        int $itemId
    ): JsonResponse {
        try {
            $this->removeVehiculeFromReservationUseCase->execute($id, $itemId);

            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}/assurance', name: 'reservation_add_assurance', methods: ['POST'])]
    public function addAssurance(
        int $id
    ): JsonResponse {
        try {
            $this->addAssuranceToReservationUseCase->execute($id);
            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}/assurance', name: 'reservation_remove_assurance', methods: ['DELETE'])]
    public function removeAssurance(
        int $id
    ): JsonResponse {
        try {
            $this->removeAssuranceFromReservationUseCase->execute($id);
            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}/payer', name: 'reservation_payer', methods: ['POST'])]
    public function payer(
        int $id
    ): JsonResponse {
        try {
            $this->payReservationUseCase->execute($id);
            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('', name: 'reservation_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        /** @var User $client */
        $client = $this->getUser();
        $reservations = $this->reservationRepository->findReservationsByClient($client);

        return $this->json($reservations, 200, [], ['groups' => ['reservation:read']]);
    }

    #[Route('/{id}', name: 'reservation_show', methods: ['GET'])]
    public function show(
        int $id
    ): JsonResponse {
        $reservation = $this->reservationRepository->findReservationById($id);

        if (!$reservation || $reservation->getClient() !== $this->getUser()) {
            return $this->json(['error' => 'Réservation non trouvée'], 404);
        }

        return $this->json($reservation, 200, [], ['groups' => ['reservation:read']]);
    }
}