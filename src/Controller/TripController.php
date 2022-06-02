<?php

namespace App\Controller;

use App\Form\model\Search;
use App\Form\SearchType;
use App\Entity\State;
use App\Repository\CampusRepository;
use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\PlaceRepository;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/trip', name: 'trip_')]
class TripController extends AbstractController
{
       #[Route('/create', name: 'create')]
    public function create(TripRepository $tripRepository, StateRepository $stateRepository, PlaceRepository $placeRepository, Request $request): Response
    {

        //création d'une nouvelle sortie
        $trip = new Trip();
        $trip
            ->setOrganiser($this->getUser())
            ->setPlace($placeRepository->findOneBy(array('name' => "Cinema")))
            ->addUser($this->getUser());

        //création du formulaire
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        //traitement du formulaire
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            //si bouton 'save'
            if ($tripForm->get('save')->isClicked()) {
                //l'état de la sortie passe à "Créée"
                $state = $stateRepository->findOneBy(array('description'=>"Créée"));
                $trip->setState($state);

                $tripRepository->add($trip, true);
                $this->addFlash("success", "Ta proposition de sortie est enregistrée!");
            }

            //si bouton 'publish'
            if ($tripForm->get('publish')->isClicked()){
                //l'état de la sortie passe à "Ouverte"
                $state = $stateRepository->findOneBy(array('description'=>"Ouverte"));
                $trip->setState($state);

                $tripRepository->add($trip, true);
                $this->addFlash("success", "Ta proposition de sortie est ajoutée!");
            }

            return $this->redirectToRoute("trip_home");

        }
        return $this->render('trip/create.html.twig',
            ['tripForm' => $tripForm->createView()]);

    }
        #[Route('/', name: 'home')]
        public function index(Request $request, TripRepository $tripRepository): Response
        {
            $search = new Search();
            $searchForm =$this->createForm(SearchType::class, $search);
            $searchForm->handleRequest($request);

            $tripList =$tripRepository->findAll();

        if ($searchForm->isSubmitted() && $searchForm->isValid()){


            }

            return $this->render('trip/home.html.twig', [
                'searchForm' => $searchForm->createView(),
                'tripList' => $tripList
            ]);
        }


}
