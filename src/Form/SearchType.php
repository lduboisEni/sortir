<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\model\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un campus',
                'required' => false
            ])
            ->add('nameContain', \Symfony\Component\Form\Extension\Core\Type\SearchType::class, [
                'label' => 'Le nom de la sortie contient ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search'
                ]
            ])
            ->add('begindate', DateType::class, [
                'label' => 'Entre ',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('enddate', DateType::class, [
                'label' => 'et ',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('isOrganiser', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice ',
                'required' => false
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e ',
                'required' => false
            ])
            ->add('isNotRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e ',
                'required' => false
            ])
            ->add('isPassed', CheckboxType::class, [
                'label' => 'Sorties passées ',
                'required' => false
            ])

            ->add('Rechercher', SubmitType::class, [
                'label' => 'Rechercher'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
