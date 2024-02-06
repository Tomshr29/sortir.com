<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/', name: 'main_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[NoReturn] #[Route('/test', name: 'test')]
    public function test(): Response
    {
        echo 'Test';
        die();
        //return $this->render('');
    }

    /*
    #[Route('/testhydratecity', name: 'testhydratecity')]
    public function testhydratecity(EntityManagerInterface $entityManager): Response
    {
        #Création d'une instance de l'entité Ville
        $city = new city();

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

    /*
    #[Route('/testhydrateplace', name: 'testhydrateplace')]
    public function testhydrateplace(EntityManagerInterface $entityManager): Response
    {
        #Création d'une instance de l'entité Ville
        $place = new place();

        // hydrate toutes les propriétés
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

}