<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class ProfileModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'] ?? null;
        $builder
            ->add('email', EmailType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlenght' => '180',
                ],
                'label' => 'Email :',
                'label_attr' => [
                    'class' => 'form_label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180]),
                ]
            ])
            ->add('username', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Pseudo :',
                'label_attr' => [
                    'class' => 'form_label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50]),
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Prénom :',
                'label_attr' => [
                    'class' => 'form_label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form_label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50]),
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' => '10',
                    'maxlenght' => '15',
                ],
                'label' => 'Téléphone :',
                'label_attr' => [
                    'class' => 'form_label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 10, 'max' => 15]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
