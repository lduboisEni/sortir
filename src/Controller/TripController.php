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
use App\Service\StateService;
use App\Service\TripService;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/trip', name: 'trip_')]
class TripController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, TripRepository $tripRepository, StateRepository $stateRepository, StateService $stateService): Response
    {
        $stateService->updateState();

        $search = new Search();
        $user = $this->getUser();

        $searchForm =$this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);

        $tripList =$tripRepository->filterBy($search, $user, $stateRepository);

        return $this->render('trip/home.html.twig', [
            'searchForm' => $searchForm->createView(),
            'tripList' => $tripList,
        ]);
    }

    #[Route('/subscribe/{id}', name: 'subscribe')]
    public function subscribe($id, TripRepository $tripRepository, StateRepository $stateRepository)
    {

        //je récupère mon user en cours
        $user = $this->getUser();
        //je récupère la sortie qui a été choisie
        $trip = $tripRepository->find($id);

        //j'ajoute le user à la liste des users de la sortie et pousse en bdd
        $trip->getUsers()->add($user);
        $tripRepository->add($trip, true);

        //je passe l'état à "Clôturée" si le nombre d'inscrit a été atteint
        if($trip->getUsers()->count() === $trip->getMaxRegistration()){
            $state =$stateRepository->findOneBy(array('description' => "Clôturée"));
            $trip->setState($state);
        }

        $this->addFlash('message', "Félicitations vous êtes inscrit !");

        return $this->redirectToRoute('trip_home');

    }

    #[Route('/unsubscribe/{id}', name: 'unsubscribe')]
    public function unsubscribe($id, TripRepository $tripRepository)
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
    public function create(TripService $tripService, TripRepository $tripRepository, StateRepository $stateRepository, PlaceRepository $placeRepository, Request $request): Response
    {

        //création d'une nouvelle sortie
        $trip = new Trip();
        $user = $this->getUser();
        $trip
            ->setOrganiser($user)
            ->setPlace($placeRepository->findOneBy(array('name' => "Cinema")))
            ->setCampus($user->getCampus())
            ->addUser($user);
        //initialisation du message add
        $message = "";

        //création du formulaire
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        //traitement du formulaire
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            //si bouton 'save'
            if ($tripForm->get('save')->isClicked()) {

                $tripService->save($trip, $tripRepository, $stateRepository);

                $message = 'Ta proposition de sortie est enregistrée!';

            }

            //si bouton 'publish'
            if ($tripForm->get('publish')->isClicked()) {

                //si la sortie est déjà créée on la publie
                if($trip->getState() === "Créée") {

                    //appel à la fonction Publish du service Trip
                    $tripService->publish($trip->getId(), $tripRepository, $stateRepository);

                    $message = "Ta proposition de sortie a été publiée !";

                //sinon on l'enregistre avant de la publier
                } else {

                    //appel à la fonction Save du service Trip
                    $tripService->save($trip, $tripRepository, $stateRepository);

                    //puis appel à la fonction Publish du service Trip
                    $tripService->publish($trip->getId(), $tripRepository, $stateRepository);

                    $message = "Ta proposition de sortie a été publiée !";

                }
            }

            $this->addFlash('message', $message);
            return $this->redirectToRoute('trip_home');
        }

        return $this->render('trip/create.html.twig',
            ['tripForm' => $tripForm->createView(),
                'user' => $user]);

    }

    #[Route('/display/{id}', name: 'display')]
    public function display($id, TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);
        return $this->render('trip/display.html.twig', [
            'id' => $id,
            'trip' => $trip
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, TripService $tripService, TripRepository $tripRepository, StateRepository $stateRepository, PlaceRepository $placeRepository, Request $request): Response
    {

        //initialisation du message add
        $message = "";

        //récupération de la sortie cliquée
        $trip = $tripRepository->find($id);

        $user = $this->getUser();

        //création du formulaire
        $tripForm2 = $this->createForm(TripType::class, $trip);
        $tripForm2->handleRequest($request);

        //traitement du formulaire
        if ($tripForm2->isSubmitted() && $tripForm2->isValid()) {

            //si bouton 'save'
            if ($tripForm2->get('save')->isClicked()) {
                  $tripService->save($trip, $tripRepository, $stateRepository);

                  $tripRepository->add($trip, true);
                  $message = "Ta proposition de sortie est enregistrée!";

            }

            //si bouton 'publish'
            if ($tripForm2->get('publish')->isClicked()){

                //si la sortie est déjà créée on la publie
                if($trip->getState() == "Créée") {
                    $tripService->publish($trip, $tripRepository, $stateRepository);

                    //sinon on l'enregistre avant de la publier
                } else {
                    $tripService->save($trip, $tripRepository, $stateRepository);
                    $tripRepository->add($trip, true);
                    $tripService->publish($trip, $tripRepository, $stateRepository);

                }
                $message = "Ta sortie a été publiée !";
            }

            $this->addFlash('message', $message);
            return $this->redirectToRoute('trip_home');
        }

        if($user === $trip->getOrganiser()) {

        return $this->render('trip/edit.html.twig',[
            'tripForm' => $tripForm2->createView(),
            'trip' => $trip,
            'id' => $id
            ]);
        }
        else {
            $this->addFlash('message', "Vous n'êtes pas l'organisateur de cette sortie !!");
            return $this->redirectToRoute('trip_home');
            }
    }

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancel($id, TripRepository $tripRepository, StateRepository $stateRepository, Request $request): Response
    {
        //récupération de l'utlisateur
        $user = $this->getUser();

        //récupération de la sortie cliquée
        $trip = $tripRepository->find($id);

        //récupération de la sortie cliquée
        $trip = $tripRepository->find($id);

        //modification du statut de la sortie
        $state = $stateRepository->findOneBy(array('description'=>"Annulée"));
        $trip->setState($state);

        if($request->isMethod('POST')) {
            //récupération du motif saisi et set de tripInfos
            $motif = $request->request->get("motif");
            $description = $trip->getTripInfos();
            $trip->setTripInfos('description : ' . $description . 'motif d annulation'. $motif );

            //mise à jour de la bdd
            $tripRepository->add($trip, true);

            //création du message
            $this->addFlash('message', "Ta sortie a été annulée !");

            return $this->redirectToRoute('trip_home');
        }

        if($user === $trip->getOrganiser()) {

        return $this->render('trip/cancel.html.twig', [
            'id' => $id,
            'trip' => $trip,
        ]);
        }
        else {
            $this->addFlash('message', "Vous n'êtes pas l'organisateur de cette sortie !!");
            return $this->redirectToRoute('trip_home');
        }
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);

        $tripRepository->remove($trip, true);

        $this->addFlash('message', 'Sortie supprimée! ');

        return $this->redirectToRoute('trip_home');
    }

    #[Route('/publish/{id}', name: 'publish')]
    public function callPublish($id, TripService $tripService, TripRepository $tripRepository, StateRepository $stateRepository)
    {
        $trip = $tripRepository->find($id);

        $tripService->publish($trip, $tripRepository, $stateRepository);

        return $this->redirectToRoute('trip_home');

    }

}
