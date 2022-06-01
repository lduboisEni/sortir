<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/trip', name: 'trip_')]
class TripController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CampusRepository $campusRepository): Response
    {
        $campusList = $campusRepository ->findBy([], ["name" => "ASC"]);
        return $this->render('trip/home.html.twig', [
            'campusList' =>$campusList
        ]);
    }

}
