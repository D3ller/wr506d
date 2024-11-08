<?php

// src/Controller/MeController.php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MeController
{
    #[Route("/api/me", name:"get_current_user", methods:["GET"])]
    public function getCurrentUser(UserInterface $user): JsonResponse
    {
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ];

        return new JsonResponse($userData);
    }
}
