<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Form\SearchVehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchVehiculesController extends AbstractController
{
    #[Route('/search/vehicules', name: 'app_search_vehicules')]
    public function index(): Response
    {
        return $this->render('search_vehicules/index.html.twig', [
            'controller_name' => 'SearchVehiculesController',
        ]);
    }

    #[Route('/search', name: 'app_search', methods: ['GET', 'POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(searchVehiculeType::class);
        $form->handleRequest($request);

        $vehiculesDisponibles = [];

        if ($form->isSubmitted() && $form->isValid()) {

            $dateDepart = $form->get('dateDepart')->getData();
            $dateRetour = $form->get('dateRetour')->getData();
            $prixMaxLocation = $form->get('prixMaxLocation')->getData();

            $vehiculesDisponibles = $entityManager->getRepository(Disponibilite::class)->findAvailableVehicules($dateDepart, $dateRetour, $prixMaxLocation);
        }    

        return $this->render('search_vehicules/search_results.html.twig', [
            'vehiculesDisponibles' => $vehiculesDisponibles,
            'form' => $form->createView(),
            'showNoResultsMessage' =>  $form->isSubmitted()
        ]);
    }
}