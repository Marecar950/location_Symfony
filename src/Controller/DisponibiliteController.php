<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Disponibilite;
use App\Form\DisponibiliteType;
use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/disponibilite')]
class DisponibiliteController extends AbstractController
{
    #[Route('/', name: 'app_disponibilite_index', methods: ['GET'])]
    public function index(DisponibiliteRepository $disponibiliteRepository): Response
    {
        return $this->render('disponibilite/index.html.twig', [
            'disponibilites' => $disponibiliteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_disponibilite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehiculeId = $request->query->get('id');
        $vehicule = $entityManager->getRepository(Vehicule::class)->find($vehiculeId);

        $disponibilite = new Disponibilite();
        $form = $this->createForm(DisponibiliteType::class, $disponibilite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($disponibilite);
            $entityManager->flush();

            $this->addFlash('success', 'La disponibilité a été ajouté avec succès.');
            return $this->redirectToRoute('app_vehicule_show', ['id' => $vehiculeId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('disponibilite/new.html.twig', [
            'form' => $form,
            'vehiculeId' => $vehiculeId,
            'vehicule' => $vehicule->getMarque() . ' - ' . $vehicule->getModele()
        ]);
    }

    #[Route('/{id}', name: 'app_disponibilite_show', methods: ['GET'])]
    public function show(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehiculeId = $request->attributes->get('id');
        $vehicule =$entityManager->getRepository(Disponibilite::class)->find($vehiculeId);

        return $this->redirectToRoute('app_vehicule_show', ['id' => $vehicule->getVehicule()->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_disponibilite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Disponibilite $disponibilite, EntityManagerInterface $entityManager): Response
    {
        $vehiculeId = $request->attributes->get('id');
        $vehicule = $entityManager->getRepository(Disponibilite::class)->find($vehiculeId);

        $form = $this->createForm(DisponibiliteType::class, $disponibilite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La disponibilité a été modifié avec succès.');
            return $this->redirectToRoute('app_vehicule_show', ['id' => $vehicule->getVehicule()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('disponibilite/edit.html.twig', [
            'vehiculeId' => $vehicule->getVehicule()->getId(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disponibilite_delete', methods: ['POST'])]
    public function delete(Request $request, Disponibilite $disponibilite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$disponibilite->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($disponibilite);
            $entityManager->flush();

            $this->addFlash('success', 'La disponibilité a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_vehicule_show', ['id' => $disponibilite->getVehicule()->getId()], Response::HTTP_SEE_OTHER);
    }
}
