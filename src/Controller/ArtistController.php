<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/artist')]
class ArtistController extends AbstractController
{
    #[Route('', name: 'app_artist_index')]
    public function index(ArtistRepository $artistRepository): Response
    {
        return $this->render('artist/index.html.twig', ['artists' => $artistRepository->findBy([], ['name' => 'ASC'])]);
    }

    #[Route('/new', name: 'app_artist_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artist);
            $em->flush();
            $this->addFlash('success', 'Selecta ajouté à la crew.');

            return $this->redirectToRoute('app_artist_index');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'titre' => 'Nouveau membre de la crew',
            'retour' => $this->generateUrl('app_artist_index'),
            'rubrique' => 'Selectas & MCs',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artist_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Artist $artist, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Membre modifié.');

            return $this->redirectToRoute('app_artist_index');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'titre' => 'Modifier : '.$artist->getName(),
            'retour' => $this->generateUrl('app_artist_index'),
            'rubrique' => 'Selectas & MCs',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_artist_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Artist $artist, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_artist_'.$artist->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($artist);
            $em->flush();
            $this->addFlash('success', 'Membre supprimé.');
        }

        return $this->redirectToRoute('app_artist_index');
    }
}
