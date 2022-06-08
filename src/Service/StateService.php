<?php

namespace App\Service;

use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class StateService
{
    private TripRepository $tripRepository;

    public function __construct(EntityManagerInterface $manager, TripRepository $tripRepository, StateRepository $stateRepository)
    {
        $this->manager = $manager;
        $this->tripRepository = $tripRepository;
        $this->stateRepository = $stateRepository;
    }

    //modification de l'état des sortie
    public function updateState()
    {

        //je récupère toutes les sorties
        $allTrips = $this->tripRepository->findAll();

        //je récupère la date du jour
        $now = new \DateTime('now');

        foreach ($allTrips as $trip) {

            //récupération de la date du début de la sortie avec un clone
            $endTimeTrip = clone $trip->getStartTime();
            //calcul la dateTime fin de sortie (date de début + durée grâce à la fonction modify())
            $endTimeTrip->modify('+' . $trip->getLenght() . 'minute');

            //récupération de la fin de la sortie avec un clone
            $archived = clone $endTimeTrip;
            //ajout d'un mois pour l'archivage
            $archived->modify('+1 month');

            //Si la date limite d'inscription est passée et
            //que le statut est Ouvert et différent de créé =>"Clôturée"
            if ($trip->getRegistrationTimeLimit() < $now &&
                $trip->getState()->getDescription() === "Ouverte" &&
                    $trip->getState()->getDescription() !== "Créée") {

                $state = $this->stateRepository->findOneBy(array('description' => "Clôturée"));
                $trip->setState($state);
            }

            //Si la date de début est passée mais
            //que la date de fin n'est pas passée avec statut "Ouvert" et différent de "Créée" =>"En cours"
            if ($trip->getStartTime() <= $now &&
                $now >= $endTimeTrip &&
                $trip->getState()->getDescription() === "Ouverte" &&
                $trip->getState()->getDescription() !== "Créée") {

                $state = $this->stateRepository->findOneBy(array('description' => "En cours"));
                $trip->setState($state);

            }

            //Si la date de fin de la sortie est passée et
            //que le statut est "Ouvert" et différent de "Créée" => "Passée"
            if ($endTimeTrip < $now &&
                $trip->getState()->getDescription() === "En cours" &&
                $trip->getState()->getDescription() !== "Créée") {

                $state = $this->stateRepository->findOneBy(array('description' => "Passée"));
                $trip->setState($state);

            }

            //Si la date de fin
            if ($archived >= $now &&
                $trip->getState()->getDescription() === "Passée" ||
                ($trip->getState()->getDescription() === "Annulée" &&
                    $trip->getState()->getDescription() !== "Créée" )) {

                $state = $this->stateRepository->findOneBy(array('description' => "Historisée"));
                $trip->setState($state);

            }

            $this->manager->persist($trip);
            $this->manager->flush();
        }

    }
}