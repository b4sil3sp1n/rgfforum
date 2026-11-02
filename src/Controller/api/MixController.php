<?php

namespace App\Controller\api;

use App\Repository\MixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// API JSON de la zone admin (cf. cours 9, Retours JSON)
#[Route('/admin/api', name: 'app_admin_api_')]
#[IsGranted('ROLE_ADMIN')]
final class MixController extends AbstractController
{
    #[Route('/mixes', name: 'mixes', methods: ['GET'])]
    public function index(MixRepository $mixRepository): Response
    {
        $mixes = $mixRepository->findAll();

        return $this->json($mixes, 200, [], ['groups' => ['api_mix_index']]);
    }
}
