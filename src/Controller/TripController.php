<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\model\Search;
use App\Form\SearchType;
use App\Entity\State;
use App\Repository\CampusRepository;
use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\PlaceRepository;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/trip', name: 'trip_')]
class TripController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, TripRepository $tripRepository): Response
    {
        $search = new Search();
        dump('1');

        $searchForm =$this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);

        $tripList =$tripRepository->filterBy($search);


        return $this->render('trip/home.html.twig', [
            'searchForm' => $searchForm->createView(),
            'tripList' => $tripList
        ]);
    }

    #[Route('/subscribe/{id}', name: 'subscribe')]
    public function subscribe($id, TripRepository $tripRepository)
    {

        //je récupère mon user en cours
        $user = $this->getUser();
        //je récupère la sortie qui a été choisie
        $trip = $tripRepository->find($id);

        $trip->getUsers()->add($user);

        $tripRepository->add($trip, true);
        dump('laul ça marche');
        $this->addFlash('message', "Félicitations vous êtes inscrit !");

        return $this->redirectToRoute('trip_home');

    }

    #[Route('/unsubscribe/{id}', name: 'unsubscribe')]
    public function cancelSubscribe($id, TripRepository $tripRepository)
    {
        //je récupère mon user en cours
        $user = $this->getUser();
        //je récupère la sortie qui a été choisie
        $trip = $tripRepository->find($id);

        //enlever le participant de la liste
        //mise à jour de la bdd
        $trip->getUsers()->removeElement($user);
        $tripRepository->add($trip, true);

        $this->addFlash('message', "Vous êtes bien désinscrit !");


        return $this->redirectToRoute('trip_home');

    }

       #[Route('/create', name: 'create')]
    public function create(TripRepository $tripRepository, StateRepository $stateRepository, PlaceRepository $placeRepository, Request $request): Response
    {

        //création d'une nouvelle sortie
        $trip = new Trip();
        $user = $this->getUser();
        $trip
            ->setOrganiser($user)
            ->setPlace($placeRepository->findOneBy(array('name' => "Cinema")))
            ->setCampus($user->getCampus())
            ->addUser($user);

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
                $this->addFlash('message', "Ta proposition de sortie est enregistrée!");
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
            ['tripForm' => $tripForm->createView(),
                'user' => $user]);

    }


    #[Route('/display/{id}', name: 'display')]
    public function display($id, TripRepository $tripRepository): Response
    {
        dump('laul ça marche ici aussi');
        $trip = $tripRepository->find($id);
        return $this->render('trip/display.html.twig', [
            'id' => $id,
            'trip' => $trip
        ]);
    }



}
