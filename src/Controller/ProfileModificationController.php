<?php

namespace App\Controller;

use App\Entity\User;
use Gedmo\Sluggable\Util\Urlizer;
use App\Form\EditUserPasswordType;
use App\Form\ProfileModificationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user', name: 'user_')]
class ProfileModificationController extends AbstractController
{

    private function isUsernameUnique(User $user, EntityManagerInterface $entityManager): bool
    {
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['username' => $user->getUsername()]);

        return $existingUser === null || $existingUser->getId() === $user->getId();
    }


    #[Route('/profil/{id}', name: 'profil')]
    public function view(int $id,UserRepository $userRepository,
                            Request $request, EntityManagerInterface $entityManager, #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        $user = $userRepository->find($id);

        return $this->render('profile/profile.html.twig', [
            'user' => $user
        ]);
    }
    #[Route('/profil/edit/{id}', name: 'edit')]
    public function edit(int $id,User $user, UserRepository $userRepository,
                            Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user){
            return $this->redirectToRoute('trip_list');
        }

        $form = $this->createForm(ProfileModificationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Check if username is already existe

            $file = $form->get('image')->getData();

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            $file->move($this->getParameter('photo_dir'), $newFilename);

            $user->setImageFileName($newFilename);

            if (!$this->isUsernameUnique($user, $entityManager)) {
                $this->addFlash('error', 'Ce Pseudo existe déjà. Veuillez en choisir un autre.');
                return $this->redirectToRoute('user_profil', ['id' => $id]);
            }

            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées'
            );
            return $this->redirectToRoute('user_profil' , ['id' => $id]);

        }
        return $this->render('profile/edit_profile.html.twig', [
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

    #[Route('/profil/other/{id}', name: 'other_profil')]
    public function otherProfil(int $id,UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('profile/otherProfile.html.twig', [
            'user' => $user
        ]);
    }

}
