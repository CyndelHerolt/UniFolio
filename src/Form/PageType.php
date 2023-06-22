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
        //----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//-----------------------Récupération des données de l'utilisateur pour choiceType--------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
        $user = $options['user'];
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $user]);
        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

//        $tracesCount = count($traces);
//        // Si l'url contient "/new" alors on ajoute 1 à $tracesCount
//        if (strpos($_SERVER['REQUEST_URI'], 'new')) {
//            $tracesCount++;
//        }
//        $tracesChoices = [];
//        for ($i = 1; $i <= $tracesCount; $i++) {
//            $tracesChoices[$i] = $i;
//        }

        $pages = [];
        foreach ($traces as $trace) {
            $pages = array_merge($pages, $trace->getPages()->toArray());
            // Si deux pages sont les mêmes, ne les afficher qu'une seule fois
            $pages = array_unique($pages, SORT_REGULAR);
        }

        //TODO: transférer cette partie dans le portfolio (c'est l'ordre de la page dans le portfolio qui ns intéresse ici) -> peut-être du coup passer l'ordre dans page_portfolio ?
        $pagesCount = count($pages);

        // Si l'url contient "/new" alors on ajoute 1 à $pagesCount
        if (strpos($_SERVER['REQUEST_URI'], 'new')) {
            $pagesCount++;
        }
        $choices = [];
        for ($i = 1; $i <= $pagesCount; $i++) {
            $choices[$i] = $i;
        }
        //----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------

        $builder
//            ->add('ordre', ChoiceType::class, [
//                'choices'=>[$choices],
//                'label' => 'Ordre',
//                'required' => true,
//                'expanded' => true,
//                'multiple' => false,
//            ])
            ->add('intitule', TextType::class, [
                'label' => 'Intitule',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);

//            ->add('trace', EntityType::class, [
//                'class' => Trace::class,
//                'choices' => $traces,
//                'label' => 'Traces',
//                'multiple' => true,
//                'choice_label' => 'titre',
//                'expanded' => true,
//                'required' => true,
//            ])
//            ;
//        foreach ($traces as $trace) {
//            $builder->add('trace', EntityType::class, [
//                'class' => Trace::class,
//                'choices' => $tracesChoices,
//                'label' => 'Traces',
//                'multiple' => true,
//                'choice_label' => 'titre',
//                'expanded' => true,
//                'required' => true,
//            ]);
//        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'user' => null,
        ]);
    }
}
