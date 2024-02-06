<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idParticipant')
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
            ->add('maxNumbRegistration')
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
