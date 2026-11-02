<?php

namespace App\Controller\api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// API JSON de la zone admin (cf. cours 9, Retours JSON)
// Le mot de passe n'a pas de #[Groups] dans l'entité User : il ne sort jamais.
#[Route('/admin/api', name: 'app_admin_api_')]
#[IsGranted('ROLE_ADMIN')]
final class UserController extends AbstractController
{
    #[Route('/users', name: 'users', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->json($users, 200, [], ['groups' => ['api_user_index']]);
    }
}
