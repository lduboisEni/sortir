<?php

namespace App\Service;

class StateService
{
//
//    //modification de l'état des sortie
//    //je récupère toutes les sorties
//$allTrips = $tripRepository->findAll();
//
//    //je récupère la date du jour
//$now = new \DateTime('now');
//
//
//foreach ($allTrips as $trip) {
//if ($trip->getState()->getDescription() != "Créée") {
//    //je calcule la dateTime à laquelle se termine la sortie (date de début + durée grâce à la fonction modify())
//$endTimeTrip = $trip->getStartTime()->modify('+' . $trip->getLenght() . 'minute');
//dump("1");
//if ($trip->getRegistrationTimeLimit()>$now) {
//$state = $stateRepository->findOneBy(array('description'=>"Ouverte"));
//$trip->setState($state);
//$tripRepository->add($trip,true);
//} dump("2");
//if ($trip->getRegistrationTimeLimit() < $now) {
//    $state = $stateRepository->findOneBy(array('description' => "Clôturée"));
//    $trip->setState($state);
//    $tripRepository->add($trip, true);
//}
//dump("3");
//if ($trip->getStartTime() <= $now && $endTimeTrip <= $now) {
//    $state = $stateRepository->findOneBy(array('description' => "En cours"));
//    $trip->setState($state);
//    $tripRepository->add($trip, true);
//}
//dump("4");
//if ($endTimeTrip < $now) {
//    $state = $stateRepository->findOneBy(array('description' => "Terminée"));
//    $trip->setState($state);
//    $tripRepository->add($trip, true);
//}
//dump("5");
//if ($endTimeTrip->modify('+1 month') >= $now) {
//    $state = $stateRepository->findOneBy(array('description' => "Historisée"));
//    $trip->setState($state);
//    $tripRepository->add($trip, true);
//}
//dump("6");
//}
//}
}