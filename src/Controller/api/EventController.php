<?php

namespace App\Controller\api;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// API JSON de la zone admin (cf. cours 9, Retours JSON)
#[Route('/admin/api', name: 'app_admin_api_')]
#[IsGranted('ROLE_ADMIN')]
final class EventController extends AbstractController
{
    #[Route('/events', name: 'events', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->json($events, 200, [], ['groups' => ['api_event_index']]);
    }
}
