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

            //je clone startTime et je le mets dans une variable
            $endTimeTrip = clone $trip->getStartTime();
            //puis je lui ajoute la durée grâce à la fonction modify()
            $endTimeTrip->modify('+' . $trip->getLenght() . 'minute');

            //je clone la dateTime à laquelle se termine la sortie
            $archived = clone $endTimeTrip;
            //puis je lui ajoute 1 mois
            $archived->modify('+1 month');

            if ($trip->getRegistrationTimeLimit() < $now &&
                $trip->getState()->getDescription() === "Ouverte" &&
                    $trip->getState()->getDescription() !== "Créée") {

                $state = $this->stateRepository->findOneBy(array('description' => "Clôturée"));
                $trip->setState($state);
            }

            if ($trip->getStartTime() <= $now &&
                $now >= $endTimeTrip &&
                $trip->getState()->getDescription() !== "Créée" &&
                ($trip->getState()->getDescription() === "Ouverte" ||
                $trip->getState()->getDescription() === "Clôturée")
                ) {

                $state = $this->stateRepository->findOneBy(array('description' => "En cours"));
                $trip->setState($state);

            }
            if ($endTimeTrip < $now &&
                $trip->getState()->getDescription() === "En cours" &&
                $trip->getState()->getDescription() !== "Créée") {

                $state = $this->stateRepository->findOneBy(array('description' => "Passée"));
                $trip->setState($state);

            }
            if ($archived <= $now &&
                $trip->getState()->getDescription() !== "Créée" &&
                ($trip->getState()->getDescription() === "Passée" ||
                    $trip->getState()->getDescription() === "Annulée" )) {

                $state = $this->stateRepository->findOneBy(array('description' => "Historisée"));
                $trip->setState($state);
            }
            $this->manager->persist($trip);
            $this->manager->flush();
        }
    }
}