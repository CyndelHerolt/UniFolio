<?php

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
            ])
            ->add('etablissement', TextType::class, [
                'label' => 'Etablissement',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Etablissement de ma formation',],
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Date de début de ma formation',],
                'help' => 'Format attendu : 01/01/2001',
                'widget' => 'single_text',
                'input_format' => 'd/m/Y',
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Date de fin de ma formation',],
                'help' => 'Format attendu : 01/01/2001',
                'widget' => 'single_text',
                'input_format' => 'd/m/Y',
            ])
            ->add('activite', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control",
                        'placeholder' => 'Activité de ma formation',
                    ],
                    'by_reference' => false,
                    'label' => 'Activité',
                    'label_attr' => ['class' => 'form-label'],
                ],
                'attr' => ['class' => 'form-activite'],
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'prototype' => true,
                'label' => false,
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
