<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use App\Repository\MixRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Les routes API JSON de l'admin sont dans src/Controller/api/
#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function index(
        EventRepository $eventRepository,
        ArtistRepository $artistRepository,
        MixRepository $mixRepository,
        UserRepository $userRepository,
    ): Response {
        return $this->render('admin/index.html.twig', [
            'nbEvents' => $eventRepository->count([]),
            'nbArtists' => $artistRepository->count([]),
            'nbMixes' => $mixRepository->count([]),
            'nbUsers' => $userRepository->count([]),
        ]);
    }
}
