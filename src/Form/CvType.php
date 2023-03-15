<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('date_creation', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'widget' => 'single_text',
                'disabled' => true,
                'label' => ' ',
            ])
            ->add('date_modification', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'widget' => 'single_text',
                'disabled' => true,
                'label' => ' ',
            ])
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Intitulé de mon CV',],
            ])
            ->add('poste', TextType::class, [
                'label' => 'Poste',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Poste de mon CV',],
            ])
            ->add('description', TextAreaType::class, [
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Description de mon CV',],
            ])
            //----------------------------------------------------------------
            ->add('soft_skills', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control",
                        'placeholder' => 'Soft Skills de mon CV',
                    ],
                    'by_reference' => false,
                    'label' => 'Soft Skills',
                    'label_attr' => ['class' => 'form-label'],
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ])
            //----------------------------------------------------------------
            ->add('hard_skills', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control",
                        'placeholder' => 'Soft Skills de mon CV',
                    ],
                    'by_reference' => false,
                    'label' => 'Soft Skills',
                    'label_attr' => ['class' => 'form-label'],
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ])
            //----------------------------------------------------------------
            ->add('langues', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control",
                        'placeholder' => 'Langues',
                    ],
                    'by_reference' => false,
                    'label' => 'Langues',
                    'label_attr' => ['class' => 'form-label'],
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ])
            //----------------------------------------------------------------
            ->add('reseaux', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control",
                        'placeholder' => 'Mes reseaux',
                    ],
                    'by_reference' => false,
                    'label' => 'Reseaux',
                    'label_attr' => ['class' => 'form-label'],
                    'help' => 'Sasissez le lien de votre profil sur le réseau social',
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ])
//----------------------------------------------------------------
            ->add('experience', CollectionType::class, [
                'entry_type' => ExperienceType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control experience",
                    ],
                    'by_reference' => false,
                    'label' => 'Experiences',
                    'label_attr' => ['class' => 'form-label'],
                    'help' => '',
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ])
        //----------------------------------------------------------------
            ->add('formation', CollectionType::class, [
                'entry_type' => FormationType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control",
                    ],
                    'by_reference' => false,
                    'label' => 'Formations',
                    'label_attr' => ['class' => 'form-label'],
                    'help' => '',
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,
            'user' => null,
            'experience' => null,
            'empty_data' => new Cv(),
        ]);
    }
}
