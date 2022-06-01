<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :'
            ])

            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone :'
            ])
            /*->add('campus', Select::class, [
                'label' => 'Campus :',
                'class' => 'App\Entity\Campus',
                'choice_label' => 'name',
            ])*/
            ->add('password')
            ->add('password')
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
