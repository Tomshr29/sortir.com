<?php

namespace App\Controller;

use App\DTO\TripDTO;
use App\Entity\Trip;
use App\Form\DTOType;
use App\Form\SearchType;
use App\Form\TripType;
use App\Model\SearchData;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $trips = $tripRepository->findAll();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $searchData->page = $request->query->getInt('page', 1);
            $trips = $tripRepository->findBySearch($searchData);



            return $this->render('trip/listTrip.html.twig', [
                'form' =>$form->createView(),
                'trips' => $trips
            ]);
        }


        $tripDTO = new TripDTO;
        $formDTO =$this->createForm(DTOType::class, $tripDTO);

        $data = $request->request->all();

        $formDTO->handleRequest($request);
        if($formDTO->isSubmitted() && $formDTO->isValid()){

            $tripDTO->setOrganizer($data['getOrganizer']);
            $tripDTO->setSubscribe($data['getSubscribe']);
            $tripDTO->setUnsubscribe($data['getUnsubscribe']);
            $tripDTO->setLastTrip($data['getLastTrip']);

            return $this->render('trip/listTrip.html.twig', [
                'formDTO' => $formDTO->createView(),
                'data' => $data
            ]);

        }


        return $this->render('trip/listTrip.html.twig', [
            'form' => $form->createView(),
            'formDTO' => $formDTO->createView(),
            'trips' => $trips,
            'data' => $data
        ]);
    }


    #[Route('/newTrip', name: 'app_newTrip', methods: ['GET'])]
    public function newTrip(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);

        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $entityManager->persist($trip);
            $entityManager->flush();
        }

        # $place = $entityManager->getRepository(Place::class)->find($id);#


            return $this->render('trip/newTrip.html.twig', [
                'tripForm' => $tripForm->createView()
            ]);

    }

    #[Route('/detailTrip', name: 'app_detailTrip')]
    public function detailTrip (): Response
    {
        return $this->render('trip/detailTrip.html.twig', [
            'Controller_Name' => 'detailTrip'
        ]);
    }

}
