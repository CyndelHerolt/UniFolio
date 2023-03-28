<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste', TextType::class, [
                'label' => 'Poste',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '',],
                'help' => 'Poste occupé dans l\'entreprise',
            ])
            ->add('entreprise', TextType::class, [
                'label' => 'Entreprise',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '',],
                'help' => 'Nom de l\'entreprise',
            ])
            ->add('date_debut', TextType::class, [
                'label' => 'Date de début',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '',],
                'help' => 'Date de début de l\'expérience',
            ])
            ->add('date_fin', TextType::class, [
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '',],
                'help' => 'Date de fin de l\'expérience',
            ])
            //----------------------------------------------------------------
            ->add('activite', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control activite",
                        'placeholder' => 'activités',
                    ],
                    'by_reference' => false,
                    'label' => 'activités',
                    'label_attr' => ['class' => 'form-label'],
                ],
                'attr' => ['class' => 'exp-activite'],
                'prototype' => true,
                'label' => false,
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
            'data_class' => Experience::class,
        ]);
    }
}
