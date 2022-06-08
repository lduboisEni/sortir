<?php

namespace App\Service;

use App\Entity\Trip;
use App\Repository\StateRepository;
use App\Repository\TripRepository;


class TripService
{

    public function save($trip, TripRepository $tripRepository, StateRepository $stateRepository)
    {

        $state = $stateRepository->findOneBy(array('description' => "Créée"));
        $trip->setState($state);

        $tripRepository->add($trip, true);

    }

    public function publish($id, TripRepository $tripRepository, StateRepository $stateRepository)
    {
        //récupération de la sortie
        $trip = $tripRepository->find($id);

        //l'état de la sortie passe à "Ouverte"
        $state = $stateRepository->findOneBy(array('description'=>"Ouverte"));
        $trip->setState($state);

        //modification de la sortie en bddd et ajout du message
        $tripRepository->add($trip, true);

    }

}

