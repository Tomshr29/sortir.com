<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie :',
                'constraints' => new NotBlank(['message'=>'Merci de renseigner le nom de la sortie'])
            ])
            ->add('dateTimeStart', DateTimeType::class,[
                'html5'=>true,
                'label'=>'Date et heure de la sortie :',
                'widget' => 'single_text',
                'by_reference' => true,
            ])
            ->add('registrationDeadline', DateType::class, [
                'html5' => true,
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
                'by_reference' => true,
            ])
            ->add('publicationDate', DateType::class, [
                'html5' => true,
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'by_reference' => true,
            ])
            ->add('duration', NumberType::class, [
                'html5' => true,
                'label'=>'Durée',
                'attr' => ['min'=>1,]
            ])
            ->add('maxNumbRegistration', NumberType::class, [
                'html5' => true,
                'label' => 'Nombre de places :',
                'data' => 5,
                'attr' => ['min' =>1,]
            ])
            ->add('tripInfo', TextareaType::class, [
                'label'=>'Description et infos :',
                'attr'=>['rows'=>5],
                'constraints' => new NotBlank(['message'=>'Merci de renseigner les infos de la sortie.'])
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder' =>'--- Choisir une ville ---',
                'label' => 'Ville :',
                'mapped' => false,
                'constraints' => new NotBlank(['message'=>'Merci de choisir une ville.']),
                'required' =>false,
            ])
//            ->add('place', EntityType::class, [
//                'class' => Place::class,
//                'choice_label' => 'name',
//                'label' => 'Sélectionnez un lieu :',
//                'placeholder' =>'--- Lieu ---',
//                'query_builder' =>function (PlaceRepository $placeRepo) {
//                    return $placeRepo->createQueryBuilder('p')->orderBy('p.name', 'ASC');
//                },
//                'constraints' => new NotBlank(['message'=>'Merci de choisir une le lieu de la sortie.']),
//            ])
            ->add('place', ChoiceType::class, [
                'label' => 'Sélectionnez un lieu :',
                'placeholder' =>'--- Lieu ---',
                'required' =>false,
            ])
        ;

        $formModifier = function (FormInterface $form, City $city = null){
            $places = null === $city ? [] : $city->getPlace();

            $form->add('place', EntityType::class, [
                'class' => Place::class,
                'choices'=>$places,
                'choice_label' => 'name',
                'label' => 'Sélectionnez un lieu :',
                'placeholder' =>'--- Lieu ---',
                'attr' => ['class' => 'custom-select'],
                //'constraints' => new NotBlank(['message'=>'Merci de choisir une le lieu de la sortie.']),
                'required' =>false,
            ]);

        };

        $builder->get('city')->addEventListener(
          FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){
              $city = $event->getForm()->getData();
              $formModifier($event->getForm()->getParent(), $city);

            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
