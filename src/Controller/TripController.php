<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\SearchType;
use App\Form\TripType;
use App\Model\SearchData;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TripController extends AbstractController
{
    #[Route('/trip', name: 'app_trip')]
    public function index(): Response
    {
        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }


    #[Route('/listTrip', name: 'app_listTrip', methods: ['GET'])]
    public function listTrip(TripRepository $tripRepository, Request $request): Response{

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $searchData->page = $request->query->getInt('page', 1);
            $trip = $tripRepository->findBySearch($searchData);

            return $this->render('trip/listTrip.html.twig', [
                'form' =>$form->createView(),
                'trip' => $trip
            ]);
        }



        return $this->render('trip/listTrip.html.twig', [
            'form' => $form->createView(),
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
