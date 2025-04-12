<?php

namespace App\Controller;

use App\Application\UseCase\Vehicule\CreateVehiculeInput;
use App\Application\UseCase\Vehicule\CreateVehiculeUseCase;
use App\Entity\Vehicule\Port\VehiculeRepositoryInterface;
use App\UseCase\Vehicule\DeleteVehiculeUseCase;
use App\UseCase\Vehicule\UpdateVehiculeInput;
use App\UseCase\Vehicule\UpdateVehiculeUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/vehicules')]
class VehiculeController extends AbstractController
{
    #[Route('/api/vehicules', name: 'vehicule_list', methods: ['GET'])]
    public function list(VehiculeRepositoryInterface $repository): JsonResponse
    {
        $vehicules = $repository->findAll();

        return $this->json($vehicules, 200, [], ['groups' => ['vehicule:read']]);
    }

    #[Route('', name: 'vehicule_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        Request $request,
        CreateVehiculeUseCase $useCase
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        try {
            $input = new CreateVehiculeInput(
                $data['marque'] ?? '',
                $data['modele'] ?? '',
                (float)($data['prixJournalier'] ?? 0)
            );

            $useCase->execute($input);
            return $this->json(['success' => true], 201);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'vehicule_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(
        int $id,
        Request $request,
        UpdateVehiculeUseCase $useCase
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        try {
            $input = new UpdateVehiculeInput(
                $id,
                $data['marque'] ?? '',
                $data['modele'] ?? '',
                (float)($data['prixJournalier'] ?? 0)
            );

            $useCase->execute($input);
            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'vehicule_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        int $id,
        DeleteVehiculeUseCase $useCase
    ): JsonResponse {
        try {
            $useCase->execute($id);
            return $this->json(['success' => true], 204);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}