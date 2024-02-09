<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
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

    #[Route('/listTrip', name: 'app_listTrip')]
    public function listTrip(): Response{
        return $this->render('trip/listTrip.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }

    #[Route('/newTrip', name: 'app_newTrip')]
    public function newTrip(): Response
    {
        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);

        return $this->render('trip/newTrip.html.twig', [
            'tripForm' => $tripForm
        ]);

    }

    #[Route('/listTrip', name: 'app_list')]
    public function list(TripRepository $tripRepository): Response
    {
        $trips = $tripRepository->findAll();
        //dd($trips);

        return $this->render('trip/list.html.twig',
        [
            'trips' => $trips
        ]);
    }

    /*
     #[Route('/testhydrateplace', name: 'testhydrateplace')]
    public function testhydrateplace(EntityManagerInterface $entityManager): Response
    {
        #Création d'une instance de l'entité Lieu
        $place = new place();

        // hydrate toutes les propriétés (Rennes)
        $place->setName('Les Champs Libres');
        $place->setStreet('10 Cr des Alliés');
        $place->setLatitude(48.105);
        $place->setLongitude(1.675);

        // hydrate toutes les propriétés (Nantes)
        $place->setName('Little Atlantique Brewery');
        $place->setStreet('23 Bd de Chantenay');
        $place->setLatitude(47.196825);
        $place->setLongitude(-1.5894648);

        dump($place);

        $entityManager->persist($place);
        $entityManager->flush();

        //$entityManager = $this->getDoctrine()->getManager();

        return $this->render('event/place.html.twig');
    }
*/

/*
    #[Route('/testhydratetrip', name: 'testhydratetrip')]
    public function testhydratetrip(EntityManagerInterface $entityManager): Response
    {
        // Création d'une instance de l'entité Trip
        $trip = new Trip();

        // Convertir la chaîne de caractères en objet DateTime pour la date de début
        $dateTimeStart = \DateTime::createFromFormat('d/m/Y H:i', '14/02/2024 18:00');

        // Convertir la chaîne de caractères en objet DateTime pour la date de début
        $Duration = \DateTime::createFromFormat('H\hi', '2h00');

        // Convertir la chaîne de caractères en objet DateTime pour la date de début
        $RegistrationDeadline = \DateTime::createFromFormat('d/m/Y H:i', '14/02/2024 20:00');

        // Vérifier si la conversion a réussi
        if ($dateTimeStart instanceof \DateTime) {
            // Hydrater toutes les propriétés
            $trip->setIdParticipant('1');
            $trip->setName('Philo');
            $trip->setDateTimeStart($dateTimeStart);

            // Stocker la durée sous forme de chaîne
            $trip->setDuration($Duration);

            $trip->setRegistrationDeadline($RegistrationDeadline);
            $trip->setMaxNumbRegistration('15');
            $trip->setTripInfo('La guerre des mondes : Séminaire d\'écologie politique avec Paul Guillibert (CNRS/ISJPS) et Frédéric Monferrand : Ces vingt dernières années, la pensée environnementale a été polarisée par...');
            $trip->setStatut('En cours');

            dump($trip);

            //$entityManager->persist($trip);
            //$entityManager->flush();
        }
        else
        {
            // Gérer l'échec de la conversion
            // Par exemple, en affichant un message d'erreur
            echo 'La conversion de la date a échoué.';
        }

        //$entityManager = $this->getDoctrine()->getManager();

        return $this->render('trip/list.html.twig');
    }
*/
}
