<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use App\Repository\MixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        EventRepository $eventRepository,
        ArtistRepository $artistRepository,
        MixRepository $mixRepository,
    ): Response {
        return $this->render('home/index.html.twig', [
            'nbEvents' => $eventRepository->count([]),
            'nbArtists' => $artistRepository->count([]),
            'nbMixes' => $mixRepository->count([]),
            'lastEvent' => $eventRepository->findNext(),
        ]);
    }
}
