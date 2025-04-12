<?php

namespace App\Controller;

use App\UseCase\User\RegisterUserInput;
use App\UseCase\User\RegisterUserUseCase;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/auth/register', name: 'user_register', methods: ['POST'])]
    public function __invoke(
        Request $request,
        RegisterUserUseCase $useCase,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        try {
            $input = new RegisterUserInput(
                email: $data['email'] ?? '',
                password: $data['password'] ?? '',
                firstName: $data['firstName'] ?? '',
                lastName: $data['lastName'] ?? '',
                driverLicenseDate: new DateTimeImmutable($data['driverLicenseDate'] ?? 'now')
            );
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Invalid input: ' . $e->getMessage()], 400);
        }

        try {
            $useCase->execute($input);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json(['success' => true], 201);
    }

    #[Route('/api/test-login', name: 'test_login')]
    public function testLogin(): Response
    {
        return new JsonResponse(['message' => 'It works']);
    }
}