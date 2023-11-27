<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Form;

use App\Entity\Page;
use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\TraceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class PageType extends AbstractType
{
    public function __construct(
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
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Intitulé de ma page',
                ],
                'help' => '100 caractères maximum',
                'required' => true,
                'label' => 'Intitulé',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('description', TextAreaType::class, [
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'Chapô de ma page',
                ],
                'help' => 'Une phrase pour résumer le contenu de la page',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'user' => null,
        ]);
    }
}
