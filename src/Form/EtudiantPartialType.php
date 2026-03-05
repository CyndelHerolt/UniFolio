<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantPartialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('mail_perso')
        ->add('telephone')
        ->add('bio')
        ->add('optAlternance', ChoiceType::class, [
        'choices' => [
            'oui' => true,
            'non' => false,
        ],
        'label' => 'Recherche d\'alternance',
        'label_attr' => ['class' => 'form-label'],
        'attr' => ['class' => "form-control"],
        'help' => 'Indiquez si vous êtes à la recherche d\'une alternance',
        'required' => true,
        'mapped' => true,
        'expanded' => true,
        'multiple' => false,
        ])
        ->add('optStage', ChoiceType::class, [
        'choices' => [
            'oui' => true,
            'non' => false,
        ],
        'label' => 'Recherche de stage',
        'label_attr' => ['class' => 'form-label'],
        'attr' => ['class' => "form-control"],
        'help' => 'Indiquez si vous êtes à la recherche d\'un stage',
        'required' => true,
        'mapped' => true,
        'expanded' => true,
        'multiple' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
