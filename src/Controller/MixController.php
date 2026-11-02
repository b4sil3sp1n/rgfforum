<?php

namespace App\Controller;

use App\Entity\Mix;
use App\Form\MixType;
use App\Repository\MixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mix')]
class MixController extends AbstractController
{
    #[Route('', name: 'app_mix_index')]
    public function index(MixRepository $mixRepository): Response
    {
        return $this->render('mix/index.html.twig', ['mixes' => $mixRepository->findBy([], ['id' => 'DESC'])]);
    }

    #[Route('/new', name: 'app_mix_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $mix = new Mix();
        $form = $this->createForm(MixType::class, $mix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($mix);
            $em->flush();
            $this->addFlash('success', 'Mix ajouté.');

            return $this->redirectToRoute('app_mix_index');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'titre' => 'Nouveau mix',
            'retour' => $this->generateUrl('app_mix_index'),
            'rubrique' => 'Mixes & Dubplates',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mix_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Mix $mix, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MixType::class, $mix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Mix modifié.');

            return $this->redirectToRoute('app_mix_index');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'titre' => 'Modifier : '.$mix->getTitle(),
            'retour' => $this->generateUrl('app_mix_index'),
            'rubrique' => 'Mixes & Dubplates',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_mix_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Mix $mix, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_mix_'.$mix->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($mix);
            $em->flush();
            $this->addFlash('success', 'Mix supprimé.');
        }

        return $this->redirectToRoute('app_mix_index');
    }
}
