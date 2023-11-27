<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Trace\Form;

use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\TraceRepository;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class TraceTypePdfType extends AbstractType
{
    public function __construct(
        protected TraceRepository $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $competences = $options['competences'];

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
            ->add('titre', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un intitulé',
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'L\'intitulé ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'Titre de ma trace',
                ],
                'help' => '100 caractères maximum',
                'required' => true,
            ])
//----------------------------------------------------------------
            ->add('contenu', CollectionType::class, [
                'entry_type' => FileType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control pdf-trace",
                        'accept' => 'pdf',
                    ],
                    'by_reference' => false,
                    'label' => false,
                    'help' => 'format accepté : pdf',
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'mapped' => true,
                'by_reference' => false,
                'empty_data' => [],
                'data' => [],
            ])
            //----------------------------------------------------------------
            ->add('legende', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une légende',
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'La légende ne peut pas dépasser 100 caractères',
                    ]),
                ],
                'label' => 'Légende',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '...'],
                'help' => 'Description de votre média, 100 caractères maximum',
                'required' => true,
            ])
            //----------------------------------------------------------------
            ->add('dateRealisation', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une date',
                    ])
                ],
                'format' => 'MM-yyyy',
                'widget' => 'single_text',
                'label' => 'Date de réalisation',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'Date à laquelle vous avez réalisé cette trace. A saisir au format mm-YYYY',
                'required' => true,
                'html5' => false,
            ])
            //----------------------------------------------------------------
            ->add('contexte', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un contexte',
                    ]),
                ],
                'label' => 'Contexte',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '...'],
                'help' => 'Le contexte dans lequel vous avez réalisé cette trace (SAE, projet personnel, en groupe, en solo ...)',
                'required' => true,
            ])
            //----------------------------------------------------------------
            ->add('description', TinymceType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre commentaire',
                    ]),
                ],
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'tinymce' ,'placeholder' => '...', 'rows' => 10, "toolbar" => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample", "menubar" => "edit view format table tools", "plugins" => "lists, table, codesample", "language" => "fr_FR"],
                'help' => 'Commentez votre trace pour justifier sa pertinence',
                'mapped' => true,
                'required' => true,
            ])
            //----------------------------------------------------------------
            ->add('competences', ChoiceType::class, [
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Veuillez sélectionner au moins une compétence',
                    ]),
                ],
                'choices' => array_combine($competences, $competences),
                'label' => false,
                'multiple' => true,
                'required' => true,
                'expanded' => true,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trace::class,
            'user' => null,
            'competences' => null,
        ]);
    }
}
