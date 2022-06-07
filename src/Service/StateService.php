<?php

namespace App\Service;

use App\Repository\StateRepository;
use App\Repository\TripRepository;

class StateService
{
    private TripRepository $tripRepository;

    public function __construct(TripRepository $tripRepository)
    {
        $this->tripRepository = $tripRepository;
    }

    //modification de l'état des sortie
    public function updateState($tripRepository, StateRepository $stateRepository)
    {

        //je récupère toutes les sorties
        $allTrips = $tripRepository->findAll();

        //je récupère la date du jour
        $now = new \DateTime('now');

        foreach ($allTrips as $trip) {
            if ($trip->getState()->getDescription() != "Créée") {
                //je calcule la dateTime à laquelle se termine la sortie (date de début + durée grâce à la fonction modify())
                $endTimeTrip = $trip->getStartTime()->modify('+' . $trip->getLenght() . 'minute');

                if ($endTimeTrip->modify('+1 month') <= $now) {

                    $state = $stateRepository->findOneBy(array('description' => "Historisée"));
                    $trip->setState($state);
                    $tripRepository->add($trip, true);

                } elseif ($endTimeTrip < $now) {

                    $state = $stateRepository->findOneBy(array('description' => "Terminée"));
                    $trip->setState($state);
                    $tripRepository->add($trip, true);

                } elseif ($trip->getStartTime() >= $now && $endTimeTrip <= $now) {

                    $state = $stateRepository->findOneBy(array('description' => "En cours"));
                    $trip->setState($state);
                    $tripRepository->add($trip, true);

                } elseif ($trip->getRegistrationTimeLimit() < $now || $trip->getUsers()->count() == $trip->getMaxRegistration()) {

                    $state = $stateRepository->findOneBy(array('description' => "Clôturée"));
                    $trip->setState($state);
                    $tripRepository->add($trip, true);

                } elseif ($trip->getRegistrationTimeLimit() > $now) {

                    $state = $stateRepository->findOneBy(array('description' => "Ouverte"));
                    $trip->setState($state);

                }
            }
        }
    }
}