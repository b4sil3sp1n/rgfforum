<?php

namespace App\Controller\api;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// API JSON de la zone admin (cf. cours 9, Retours JSON)
#[Route('/admin/api', name: 'app_admin_api_')]
#[IsGranted('ROLE_ADMIN')]
final class ArtistController extends AbstractController
{
    #[Route('/artists', name: 'artists', methods: ['GET'])]
    public function index(ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();

        return $this->json($artists, 200, [], ['groups' => ['api_artist_index']]);
    }
}
