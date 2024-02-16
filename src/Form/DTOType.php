<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DTOType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('dateTimeStart', DateTimeType::class, [
                'label' => 'Entre',
                'required' => false,
            ])

            ->add('dateTimeEnd', DateTimeType::class, [
                'label' => 'Et',
                'required' => false,
            ])

            ->add('organizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])

            ->add('subscribe', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])

            ->add('unsubscribe', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])

            ->add('lastTrip', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => 'false'/* A Verifier*/
        ]);
    }

}

