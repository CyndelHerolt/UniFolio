<?php

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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
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
    ): void
    {
        $user = $options['user'];
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $user]);
        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

        $builder
            ->add('ordre', ChoiceType::class, [
                'choices'=>[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'label' => 'Ordre',
            ])
            ->add('intitule', TextType::class, [
                'label' => 'Intitule',
            ])

            ->add('trace', EntityType::class, [
                'class' => Trace::class,
                'choices' => $traces,
                'label' => 'Traces',
                'multiple' => true,
                'choice_label' => 'titre',
                'expanded' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
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