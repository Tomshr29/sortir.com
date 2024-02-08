<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints as Assert;

class EditUserPasswordType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr'=> [
                        'class' => 'form_control'
                    ],
                    'label' => 'Ancien mot de passe',
                    'label_attr' => [
                        'class' => 'form_label'
                    ]
                ],
                'second_options' => [
                    'attr'=> [
                        'class' => 'form_control'
                    ],
                    'label' => 'Confirmation de l\'ancien mot de passe'
                ],
                'label_attr' => [
                    'class' => 'form_label'
                ],
                'invalid_message' => 'Les mots de passe de correspondent pas',
                'constraints'=>[
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                        'max' => 4096]),
                ],
            ])
            ->add('newPassword', PasswordType::class,[
                'attr' => ['class'=> 'form-control'],
                'label'=> 'Nouveau mot de passe',
                'label_attr' => [
                    'class' => 'form_label'
                    ],
                'constraints'=>[
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                        'max' => 4096]),
                ],
            ])
        ;
    }
}