<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de la sortie: "
            ])
            ->add('startTime', DateTimeType::class, [
                'label' => "Date et heure de la sortie: "
            ])
            ->add('registrationTimeLimit', DateType::class, [
                'label' => "Date limite d'inscription: "
            ])
            ->add('maxRegistration', IntegerType::class, [
                'label' => "Nombre de places: "
            ])
            ->add('lenght', IntegerType::class, [
                'label' => "Durée: "
            ])
            ->add('tripInfos', TextareaType::class, [
                'label' => "Description et infos: "
            ])
            ->add('campus', EntityType::class, [
                'label' => "Campus: ",
                'choice_label' => "name",
                'class' => 'App\Entity\Campus'
            ])
            ->add('place', EntityType::class, [
                'class' => 'App\Entity\Place'
            ] )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
