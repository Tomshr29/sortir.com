<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class CampusController extends AbstractController
{
    #[Route('/campus', name: 'app_campus')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campusForm = $this->createForm(CampusFormType::class);

        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){
            $campus = $campusForm->getData();

            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Campus ajouté avec succès.');

            return $this->redirectToRoute('app_campus', ['#campusList']);
        }

        $campusList = $entityManager->getRepository(Campus::class)->findAll();

        return $this->render('campus/index.html.twig', [
            'campusForm' => $campusForm->createView(),
            'campusList' => $campusList,
        ]);
    }
}