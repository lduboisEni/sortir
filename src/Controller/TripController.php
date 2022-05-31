<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/trip', name: 'app_trip')]
    public function index(): Response
    {
        return $this->render('trip/login.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }
}
