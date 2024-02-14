<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/campus', name: 'campus_')]
class CampusController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(CampusRepository $campusRepository, Request $request, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory): Response
    {
     $campuses = $campusRepository->findAll();
    
     $campus = new Campus();

     $campusForm = $formFactory->createBuilder(CampusFormType::class, $campus)
        ->getForm();

     $campusForm->handleRequest($request);

     if ($campusForm->isSubmitted() && $campusForm->isValid()) {

        $formData = $campusForm->getData();

        $campus->setName($formData->getName());

        $entityManager->persist($campus);
        $entityManager->flush();

        $this->addFlash('success', 'Le nouveau campus a bien été ajouté');
        return $this->redirectToRoute('campus_list');
     }
     return $this->render('campus/list.html.twig', [
        'campuses' => $campuses,
        'campusForm' => $campusForm->createView()
    ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(CampusFormType::class, $campus);

        dump($campus);
        $campusForm->handleRequest($request);
        dump($campus);

        if ($campusForm->isSubmitted() && $campusForm->isValid())
        {
            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash('success', 'Le nouveau campus a bien été ajouté');
            return $this->redirectToRoute('campus_create');
        }

        return $this->render('campus/createCampus.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, CampusRepository $campusRepository, int $id): Response
    {
        $campus = $campusRepository->find($id);

        if (!$campus) {
            throw $this->createNotFoundException('Le campus n\'existe pas');
        }

        $campusForm = $this->createForm(CampusFormType::class, $campus);
        dump($campus);
        $campusForm->handleRequest($request);
        dump($campus);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La ville a bien été modifiée');
            return $this->redirectToRoute('campus_list');
        }

        return $this->render('campus/edit.html.twig', [
            'campusForm' => $campusForm->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, Campus $campus): Response
    {
        $entityManager->remove($campus);
        $entityManager->flush();

        $this->addFlash('success', 'Le campus a bien été supprimée');
        return $this->redirectToRoute('campus_list');
    }
}
