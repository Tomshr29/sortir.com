<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserPasswordType;
use App\Form\ProfileModificationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class ProfileModificationController extends AbstractController
{
    #[Route('/profil/{id}', name: 'profil')]
    public function modifie(int $id,UserRepository $userRepository,
                            Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user){
            return $this->redirectToRoute('app_listTrip');
        }

        $form = $this->createForm(ProfileModificationType::class, $user);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           $user = $form->getData();
           $entityManager->persist($user);
           $entityManager->flush();

           $this->addFlash(
               'success',
               'Les informations de votre compte ont bien été modifiées'
           );
           return $this->redirectToRoute('user_profil' , ['id' => $id]);

       }
        return $this->render('profile/profile.html.twig', [
            'form_edit_profile' => $form->createView()
        ]);
    }

    #[Route('profil/edition-mot-de-passe/{id}', name: 'edit_password', methods: ['GET','POST'])]
    public function editPassword(int $id,UserRepository $userRepository,
                                 Request $request, UserPasswordHasherInterface $hasher,EntityManagerInterface $entityManager) :Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(EditUserPasswordType::class);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            if($hasher->isPasswordValid($user, $form->getData()['Password'])){
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->getData()['newPassword']
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié'
                );

                return $this->redirectToRoute('user_profil', ['id' => $id]);
            }else {
                foreach ($form->getErrors(true) as $error) {
                    dump($error->getMessage());
                }
                $this->addFlash(
                    'success',
                    'Le mot de passe est incorrect'
                );
            }
        }

        return $this->render('profile/edit_password.html.twig', [
            'form_edit_password'=> $form->createView()
        ]);
    }
}
