<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityCreationFormType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/citys', name: 'city_')]
class CityController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(CityRepository $cityRepository, Request $request, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory): Response
    {
        $citys = $cityRepository->findAll();

        $city = new City();

        // Create form with FormBuilderInterface
        $cityForm = $formFactory->createBuilder(CityCreationFormType::class, $city)
            ->getForm();

        // Handle form submission
        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            // Get form data
            $formData = $cityForm->getData();

            // Set City object properties
            $city->setName($formData->getName());
            $city->setPostalCode($formData->getPostalCode());

            // Persist and flush to database
            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'La nouvelle ville a bien été ajoutée');
            return $this->redirectToRoute('city_list');
        }
        return $this->render('city/list.html.twig', [
            'citys' => $citys,
            'cityForm' => $cityForm
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();
        $cityForm = $this->createForm(CityCreationFormType::class, $city);

        dump($city);
        $cityForm->handleRequest($request);
        dump($city);

        if ($cityForm->isSubmitted() && $cityForm->isValid())
        {
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('success', 'La nouvelle ville a bien été ajouté');
            return $this->redirectToRoute('city_create');
        }

        return $this->render('city/createCity.html.twig', [
            'cityForm' => $cityForm->createView()
        ]);
    }

/*
#[Route('/testhydratecity', name: 'testhydratecity')]
public function testhydratecity(EntityManagerInterface $entityManager): Response
{
    #Création d'une instance de l'entité Ville
    $city = new city();

     // hydrate toutes les propriétés
    $city->setName('Rennes');
    $city->setpostalCode('35000');

    // hydrate toutes les propriétés
    $city->setName('Nantes');
    $city->setpostalCode('44000');

    dump($city);

    $entityManager->persist($city);
    $entityManager->flush();

    //$entityManager = $this->getDoctrine()->getManager();

    return $this->render('event/city.html.twig');
}
*/

}