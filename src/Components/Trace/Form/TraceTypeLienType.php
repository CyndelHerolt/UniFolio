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
        $user = $options['user'];
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $user]);
        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

//        $tracesCount = count($traces);
//
//        // Si l'url contient "/new" alors on ajoute 1 à $tracesCount
//        if (strpos($_SERVER['REQUEST_URI'], 'formulaire')) {
//            $tracesCount++;
//        }
//        $choices = [];
//        for ($i = 1; $i <= $tracesCount; $i++) {
//            $choices[$i] = $i;
//        }

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
//            ->add('ordre', ChoiceType::class, [
//                'choices' => [$choices],
//                'label' => 'Ordre',
//                'required' => true,
//                'expanded' => true,
//                'multiple' => false,
//            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
            ])
//----------------------------------------------------------------
            ->add('contenu', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control lien_trace",
                        'placeholder' => 'Lien',
                    ],
                    'by_reference' => false,
                    'label' => false,
                    'label_attr' => ['class' => 'form-label'],
                    'help' => 'Saisissez un lien valide',
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
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
            ])
            //----------------------------------------------------------------
            ->add('competences', ChoiceType::class, [
                'choices' => array_combine($competences, $competences),
                'label' => false,
                'multiple' => true,
                'required' => true,
                'expanded' => true,
                'mapped' => false,
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
