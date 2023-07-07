<?php


namespace App\Components\Trace\Form;

use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Service\Attribute\Required;


class TraceTypeLienType extends AbstractType
{
public function __construct(
    protected TraceRepository     $traceRepository,
    public BibliothequeRepository $bibliothequeRepository,
    #[Required] public Security   $security
)
{
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
                'entry_type' => TextType::class,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Veuillez ajouter au moins un lien',
                    ]),
                ],
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control lien_trace",
                        'placeholder' => 'Lien',
                    ],
                    'by_reference' => false,
                    'label' => false,
                    'label_attr' => ['class' => 'form-label'],
                    'help' => 'Format du lien : https://www.exemple.com',
                ],
                'prototype' => true,
                'label' => 'Lien',
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
                'mapped' => true,
            ])
            //----------------------------------------------------------------
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre commentaire',
                    ]),
                ],
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'Commentez votre trace pour justifier sa pertinence',
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
                'attr' => [
                    'class' => "form-check"
                ],
            ])
        ;
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
