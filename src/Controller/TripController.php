<?php

namespace App\Controller;

use App\DTO\TripDTO;
use App\Entity\Shape;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\DTOType;
use App\Form\SearchType;
use App\Form\TripType;
use App\Model\SearchData;
use App\Repository\ShapeRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTime;


#[Route('/trips', name: 'trip_')]
class TripController extends AbstractController
{
    #[Route('/trip', name: 'app_trip')]
    public function index(): Response
    {
        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }

    public function currentDateTime(): DateTime
    {
        // Récupérez la date et l'heure actuelles
        $dateTime = new DateTime();

        // Ajoutez une heure à la date et à l'heure actuelles
        $dateTime->modify('+1 hour');

        // Retournez la date et l'heure modifiées
        return $dateTime;
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(TripRepository $tripRepository, Request $request): Response
    {
        $user = $this->getUser();

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $trips = $tripRepository->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $searchData->page = $request->query->getInt('page', 1);
            $trips = $tripRepository->findBySearch($searchData);

            return $this->render('trip/listTrip.html.twig', [
                'form' => $form->createView(),
                'trips' => $trips,
                'currentDateTime' => $this->currentDateTime(), // Passer la date et l'heure actuelles à la vue Twig
            ]);
        }


        $tripDTO = new TripDTO;
        $formDTO =$this->createForm(DTOType::class, $tripDTO);


        $data = $request->request->all();

        $formDTO->handleRequest($request);
        if($formDTO->isSubmitted() && $formDTO->isValid()){

            $now = new \DateTime();
            $data = $tripRepository->getByDate($now);

            $tripDTO->setOrganizer($data['getOrganizer']);
            $tripDTO->setSubscribe($data['getSubscribe']);
            $tripDTO->setUnsubscribe($data['getUnsubscribe']);
            $tripDTO->setLastTrip($data['getLastTrip']);

            return $this->render('trip/listTrip.html.twig', [
                'formDTO' => $formDTO->createView(),
                'data' => $data,
            ]);

        }


        return $this->render('trip/listTrip.html.twig', [
            'form' => $form->createView(),
            'trips' => $trips,
            'currentDateTime' => $this->currentDateTime(), // Passer la date et l'heure actuelles à la vue Twig
            'user' => $user,
            'formDTO' => $formDTO->createView(),
            'data' => $data
        ]);
    }

    #[Route('/newTrip', name: 'newTrip')]
    public function newTrip(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $campus = $user->getCampus();

        $trip = new Trip();
        //application de la Shape Id 1 : "En création"
        $shapeRepository = $entityManager->getRepository(Shape::class);
        $shape = $shapeRepository->findOneBy(['id' => 1]);

         $trip->setShape($shape);
         $trip->setCampus($campus);
         $trip->setOrganizer($user);

        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->addFlash(
                'success',
                'La sortie a bien été créée!'
            );

            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('trip_list');
        }

        return $this->render('trip/newTrip.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

//    #[Route('/publier/{id}', name: 'publish')]
//    public function publishedTrip(Trip $trip, ShapeRepository $shapeRepository, EntityManagerInterface $entityManager): Response
//    {
//        $shape = $shapeRepository->findOneBy(['id' => 2]);
//
//        $trip->setShape($shape);
//
//        $entityManager->persist($trip);
//        $entityManager->flush();
//
//        return $this->render('trip/show.html.twig', [
//            'trip' => $trip,
//        ]);
//    }

    #[Route('/publier/{id}', name: 'publish')]
    public function publishedTrip(Trip $trip, ShapeRepository $shapeRepository, EntityManagerInterface $entityManager): Response
    {
        $shape = $shapeRepository->findOneBy(['id' => 2]);
        $trip->setShape($shape);
        $entityManager->flush();

        return $this->render('trip/publisedTrip.html.twig', [
            'trip' => $trip,
        ]);
    }

    #[Route('/trip/{id}', name: 'show')]
    public function show(Trip $trip): Response
    {
        $users = $trip->getParticipants();
        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'users'=>$users,
        ]);
    }

    #[Route('/trip-register/{id}', name: 'trip-register')]
    public function tripRegister(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie si l'utilisateur récupéré est bien une instance de l'entité User
        if (!$user instanceof User) {
            // Gérez le cas où l'utilisateur n'est pas valide
            throw new \LogicException('Utilisateur non valide.');
        }

        // Récupère la sortie en fonction de l'ID passé dans la route
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        // Vérifie si la sortie existe
        if (!$trip) {
            throw $this->createNotFoundException('Sortie non trouvée.');
        }

        // Vérifie si l'utilisateur est déjà inscrit à la sortie
        if ($trip->getParticipants()->contains($user)) {
            // Ajoutez ici la logique pour gérer le cas où l'utilisateur est déjà inscrit
            // Par exemple, redirigez-le vers une autre page avec un message d'erreur
            return $this->redirectToRoute('trip_trip-register'); // Redirige vers la liste des sorties
        }

        // Ajout l'utilisateur à la liste des participants de la sortie
        $trip->addParticipant($user);

                $entityManager->flush();

        $this->addFlash('success', 'Vous inscription à cette sortie a été validée avec succès.');
        return $this->redirectToRoute('trip_list'); // Redirige vers la liste des sorties
    }

    #[Route('/unsubscribe-trip/{id}', name: 'unsubscribe-trip')]
    public function unsubscribeTrip(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->addFlash('error', 'Vous devez être connecté pour vous désinscrire de la sortie.');
            return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion
        }

        // Récupérer le trip
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('Sortie non trouvée.');
        }

        // Vérifier si l'utilisateur est inscrit à cette sortie
        if (!$trip->getParticipants()->contains($user)) {
            $this->addFlash('error', 'Vous n\'êtes pas inscrit à cette sortie.');
            return $this->redirectToRoute('trip_list'); // Rediriger vers la liste des sorties
        }

        // Supprimer l'utilisateur de la liste des participants de la sortie
        $trip->removeParticipant($user);

        // Enregistrer les modifications
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez été désinscrit de la sortie avec succès.');
        return $this->redirectToRoute('trip_list'); // Rediriger vers la liste des sorties
    }

    /*
    #[Route('/testhydratetrip', name: 'testhydratetrip')]
    public function testhydratetrip(EntityManagerInterface $entityManager): Response
    {
        // Création d'une instance de l'entité Trip
        $trip = new Trip();

        // Convertir la chaîne de caractères en objet DateTime pour la date de début
        $dateTimeStart = \DateTime::createFromFormat('d/m/Y H:i', '14/02/2024 18:00');

        // Convertir la chaîne de caractères en objet DateTime pour la durée
        $duration = \DateTime::createFromFormat('H\hi', '2h00');

        // Convertir la chaîne de caractères en objet DateTime pour la date limite d'inscription
        $registrationDeadline = \DateTime::createFromFormat('d/m/Y H:i', '14/02/2024 20:00');

        // Récupérer les entités Place, Shape, Campus et User à partir de leurs identifiants
        $placeId = 1; // Remplacez par l'identifiant de la place
        $shapeId = 1; // Remplacez par l'identifiant de la shape
        $campusId = 1; // Remplacez par l'identifiant du campus
        $organizerId = 1; // Remplacez par l'identifiant de l'organizer

        $place = $entityManager->getRepository(Place::class)->find($placeId);
        $shape = $entityManager->getRepository(Shape::class)->find($shapeId);
        $campus = $entityManager->getRepository(Campus::class)->find($campusId);
        $organizer = $entityManager->getRepository(User::class)->find($organizerId);

        // Vérifier si la conversion a réussi et si les entités existent
        if ($dateTimeStart instanceof \DateTime && $duration instanceof \DateTime && $registrationDeadline instanceof \DateTime && $place && $shape && $campus && $organizer) {
            // Hydrater toutes les propriétés
            $trip->setPlace($place);
            $trip->setShape($shape);
            $trip->setCampus($campus);
            $trip->setOrganizer($organizer);
            $trip->setName('Philo');
            $trip->setDateTimeStart($dateTimeStart);
            $trip->setDuration($duration);
            $trip->setRegistrationDeadline($registrationDeadline);
            $trip->setMaxNumbRegistration(15); // Entier, pas besoin de le mettre entre guillemets
            $trip->setTripInfo('La guerre des mondes : Séminaire d\'écologie politique avec Paul Guillibert (CNRS/ISJPS) et Frédéric Monferrand : Ces vingt dernières années, la pensée environnementale a été polarisée par...');
            $trip->setStatut('Ouverte');

            // Persist et flush vers la base de données
            $entityManager->persist($trip);
            $entityManager->flush();
        } else {
            // Gérer l'échec de la conversion ou l'absence d'une des entités
            // Par exemple, en affichant un message d'erreur
            echo 'La conversion de la date a échoué ou une des entités n\'existe pas.';
        }

        return $this->render('trip/list.html.twig');
    }
*/
}