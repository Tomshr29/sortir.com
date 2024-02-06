<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'] ?? null;
        $builder
            ->add('username', TextType::class, [
                'label' =>'Pseudo'
            ])
            ->add('firstname', TextType::class, [
                'label' =>'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' =>'Nom'
            ])
            ->add('phoneNumber', TextType::class, [
                'label' =>'Téléphone'
            ])
            ->add('email')
            ->add('password',  PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'data' => $user ? null : '',
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
