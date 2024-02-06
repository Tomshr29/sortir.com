<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/trip', name: 'app_trip')]
    public function index(): Response
    {
        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }

    #[Route('/newTrip', name: 'app_newTrip')]
    public function newTrip(): Response
    {
        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);



        return $this->render('trip/newTrip.html.twig', [
            'tripForm' => $tripForm->createView()
        ]);

    }
}
