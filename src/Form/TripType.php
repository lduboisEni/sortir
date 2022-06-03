<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de la sortie: "
            ])
            ->add('startTime', DateTimeType::class, [
                'label' => "Date et heure de la sortie: ",
                'widget' => 'single_text'
            ])
            ->add('registrationTimeLimit', DateType::class, [
                'label' => "Date limite d'inscription: ",
                'widget' => 'single_text'
            ])
            ->add('maxRegistration', IntegerType::class, [
                'label' => "Nombre de places: "
            ])
            ->add('lenght', IntegerType::class, [
                'label' => "Durée (en minutes): ",
                'attr' => [
                    'placeholder' => 90
                ]
            ])
            ->add('tripInfos', TextareaType::class, [
                'label' => "Description et infos: "
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => "Campus: ",
                'choice_label' => "name"
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'label' => "Ville",
                'choice_label' => "name",
                'mapped' => false
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'label' => "Lieu",
                'choice_label' => "name",
              ])
            ->add('save', SubmitType::class, [
                'label' => "Enregister",
            ])
            ->add('publish', SubmitType::class, [
                'label' => "Publier la sortie"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
