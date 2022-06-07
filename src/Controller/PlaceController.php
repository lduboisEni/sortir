<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/place', name: 'place_')]
class PlaceController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(PlaceRepository $placeRepository, Request $request): Response
    {
        $place = new Place();

        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()){

            $placeRepository -> add($place, true);
            $this->addFlash("success", "Lieu ajouté avec succès");

            return $this->redirectToRoute("trip_create");
        }

        return $this->render('place/create.html.twig', [
            'placeForm' => $placeForm->createView(),
        ]);
    }
}
