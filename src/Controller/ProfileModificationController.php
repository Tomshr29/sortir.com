<?php

namespace App\Controller;

use App\Form\ProfileModificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/profil", name: 'profile_')]
class ProfileModificationController extends AbstractController
{
    #[Route("/", name: 'modification')]
    public function modifie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileModificationType::class, $user);

       $profileForm->handleRequest($request);

       if($profileForm->isSubmitted() && $profileForm->isValid()){
           $entityManager->persist($user);
           $entityManager->flush();
       }


        return $this->render('profile/profile.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }
}
