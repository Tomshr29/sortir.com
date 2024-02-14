<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateTimeStart')
            /*->add('duration', DateIntervalType::class, [
                'widget' => 'choice',
                'with_minutes' => true,
                'minutes' => array_combine(range(1, 60), range(1, 60))
            ])*/
            ->add('duration', DateIntervalType::class, [
                'widget' => 'choice',
                'with_years' => false,
                'with_months' => false,
                'with_days' => true,
                'days' => range(1, 32),
                'with_hours' => true,
                'hours' => range(1, 24),
                'with_minutes' => true,
                'minutes' => array_combine(range(1, 60), range(1, 60))
            ])
            ->add('registrationDeadline')
            ->add('maxNumbRegistration', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->add('tripInfo', TextareaType::class)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    '---Choose a status---' => 'choice',
                    'Created' => 'Created',
                    'Opened' => 'Opened',
                    'Closed' => 'Closed',
                    'Activity in course' => 'Activity in course',
                    'Passed' => 'Passed',
                    'Canceled' => 'Canceled'
                ]
            ])
            ->add('campus', EntityType::class, [
                    'class' => City::class,
                    'choice_label' => 'name'
            ])

            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => function (Place $place):string
                {return $place->getName()." ".$place->getStreet();}
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
