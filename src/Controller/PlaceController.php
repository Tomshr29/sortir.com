<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Form\PlaceCreationFormType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/places', name: 'place_')]
class PlaceController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory): Response
    {
        $placeRepository = $entityManager->getRepository(Place::class);
        $places = $placeRepository->findAll();

        $cityRepository = $entityManager->getRepository(City::class);
        $cities = $cityRepository->findAll();

        $place = new Place();

        // Create form with FormBuilderInterface
        $placeForm = $formFactory->createBuilder(PlaceCreationFormType::class, $place)
            ->getForm();

        // Handle form submission
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            // Get form data
            $formData = $placeForm->getData();

            // Set City object properties
            $place->setName($formData->getName());
            $place->setStreet($formData->getStreet());

            // Persist and flush to database
            $entityManager->persist($place);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau lieu a bien été ajouté');
            return $this->redirectToRoute('place_list');
        }
        return $this->render('place/list.html.twig', [
            'places' => $places,
            'cities' => $cities,
            'placeForm' => $placeForm->createView()
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $place = new Place();
        $placeForm = $this->createForm(PlaceCreationFormType::class, $place);

        dump($place);
        $placeForm->handleRequest($request);
        dump($place);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            $entityManager->persist($place);
            $entityManager->flush();
            $this->addFlash('success', 'Le nouveau lieu a bien été ajouté');
            return $this->redirectToRoute('place_create');
        }

        return $this->render('place/createPlace.html.twig', [
            'placeForm' => $placeForm->createView()
        ]);
    }


    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, PlaceRepository $placeRepository, int $id): Response
    {
        $place = $placeRepository->find($id);

        if (!$place) {
            throw $this->createNotFoundException('Le lieu n\'existe pas');
        }

        $placeForm = $this->createForm(PlaceCreationFormType::class, $place);
        dump($place);
        $placeForm->handleRequest($request);
        dump($place);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu a bien été modifié');
            return $this->redirectToRoute('place_list');
        }

        return $this->render('place/edit.html.twig', [
            'placeForm' => $placeForm->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, Place $place): Response
    {
        // Remove the city from the database
        $entityManager->remove($place);
        $entityManager->flush();

        // Redirect to the city list page with a success message
        $this->addFlash('success', 'Le lieu a bien été supprimé');
        return $this->redirectToRoute('place_list');
    }

    /*
    #[Route('/testhydrateplace', name: 'testhydrateplace')]
    public function testhydrateplace(EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'entité City correspondant à l'ID
        $cityRepository = $entityManager->getRepository(City::class);
        $city = $cityRepository->find(3); // Remplacez 1 par l'ID de la ville correspondante

        // Vérifie si la ville existe
        if (!$city) {
            throw $this->createNotFoundException('La ville n\'existe pas');
        }

        // Création d'une instance de l'entité Place
        $place1 = new Place();
        $place1->setCity($city); // Définition de l'entité City associée

        // Hydratation des autres propriétés
        $place1->setName('Les Champs Libres');
        $place1->setStreet('10 Cr des Alliés');
        $place1->setLatitude(48.105);
        $place1->setLongitude(1.675);

        $place2 = new Place();
        $place2->setCity($city); // Définition de l'entité City associée

        // Hydratation des autres propriétés
        $place2->setName('Little Atlantique Brewery');
        $place2->setStreet('23 Bd de Chantenay');
        $place2->setLatitude(47.196825);
        $place2->setLongitude(-1.5894648);

        // Persist et flush des entités
        $entityManager->persist($place1);
        $entityManager->persist($place2);
        $entityManager->flush();

        return $this->render('place/place.html.twig');
    }
    */

}