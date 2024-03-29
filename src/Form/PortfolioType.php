<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Form;

use App\Entity\Page;
use App\Entity\Portfolio;
use App\Repository\BibliothequeRepository;
use App\Repository\PageRepository;
use App\Repository\TraceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class PortfolioType extends AbstractType
{
    public function __construct(
        protected PageRepository $pageRepository,
        protected TraceRepository $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security $security
    ) {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('date_creation', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'widget' => 'single_text',
                'disabled' => true,
                'label' => 'Date de création',
            ])
            ->add('date_modification', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'widget' => 'single_text',
                'disabled' => true,
                'label' => 'Date de modification',
            ])
            ->add('intitule', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un intitulé',
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'L\'intitulé ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Intitulé',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'Intitulé de mon portfolio',
                    'maxlength' => 100,
                    ],
                'help' => '100 caractères maximum',
                'required' => true,
            ])
            ->add('banniere', FileType::class, [
                'label' => 'Bannière',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => "form-control",
                    'accept' => '.jpg, .jpeg, .png, .gif, .svg, .webp'
                ],
                'help' => 'formats acceptés : jpg, jpeg, png, gif, svg, webp',
                'required' => false,
                'mapped' => false,
            ])
            ->add('description', TextAreaType::class, [
                'label' => 'Paragraphe d\'introduction',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'Description de mon portfolio',
                ],
                'help' => 'Un court paragraphe pour présenter votre portfolio',
            ])
            ->add('visibilite', ChoiceType::class, [
                'choices' => [
                    'Public' => true,
                    'Privé' => false,
                ],
                'label' => 'Visibilité',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'Choisissez la visibilité de votre portfolio',
                'required' => true,
                'mapped' => true,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('optSearch', ChoiceType::class, [
                'choices' => [
                    'Afficher ma recherche d\'alternance ou de stage sur mon portfolio' => true,
                    'Ne pas afficher ma recherche d\'alternance ou de stage' => false,
                ],
                'label' => 'Option alternance ou stage',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'mapped' => true,
                'help' => 'Un petit badge signalera que vous êtes à la recherche d\'une alternance ou d\'un stage',
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
            'user' => null,
        ]);
    }
}
