<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('diplome', TextType::class, [
                'label' => 'Diplôme',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Diplôme de ma formation',],
                'required' => false,
            ])
            ->add('etablissement', TextType::class, [
                'label' => 'Etablissement',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Etablissement de ma formation',],
                'required' => false,
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Date de début de ma formation',],
                'help' => 'Format attendu : 01/01/2001',
                'widget' => 'single_text',
                'input_format' => 'd/m/Y',
                'required' => false,
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Date de fin de ma formation',],
                'help' => 'Format attendu : 01/01/2001',
                'widget' => 'single_text',
                'input_format' => 'd/m/Y',
                'required' => false,
            ])
            ->add('activite', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control formation-activite",
                        'placeholder' => 'Activités de ma formation',
                    ],
                    'by_reference' => false,
                    'label' => false,
                    'label_attr' => ['class' => 'form-label'],
                ],
                'attr' => ['class' => 'form-activite'],
                'prototype' => true,
                'label' => 'Activités',
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ])
//            ->add('cvs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
