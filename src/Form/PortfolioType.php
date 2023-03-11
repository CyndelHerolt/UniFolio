<?php

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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PortfolioType extends AbstractType
{
    public function __construct(
        protected PageRepository      $pageRepository,
        protected TraceRepository     $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security   $security
    )
    {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array                $options,
    ): void
    {
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//-----------------------Récupération des données de l'utilisateur pour choiceType--------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------

        //Récupérer la bibliothèque de l'utilisateur connecté
        $user = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $user]);

        // Récupérer les traces de la bibliothèque
        $traces = $biblio->getTraces();
        if ($traces->isEmpty()) {
            $add = false;
        } else {
            $add = true;
        }
        // Récupérer les pages associées aux traces(donc les pages de l'étudiant connecté)
        $pages = [];
        foreach ($traces as $trace) {
            $pages = array_merge($pages, $trace->getPages()->toArray());
//            Si deux pages sont les mêmes, ne les afficher qu'une seule fois
            $pages = array_unique($pages, SORT_REGULAR);
        }

//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
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
                'label' => 'Intitulé',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Intitulé de mon portfolio',],
                'help' => '100 caractères maximum',
            ])
            ->add('banniere', FileType::class, [
                'label' => 'Bannière',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'formats acceptés : jpg, jpeg, png, gif, svg, webp',
                'required' => true,
                'mapped' => false,
            ])
            ->add('pages', EntityType::class, [
                'class' => Page::class,
                'choices' => $pages,
                'choice_label' => 'intitule',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Pages',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => ""],
                'help' => 'Choisissez les pages à inclure dans votre portfolio',
                'required' => true,
            ])
            ->add('visibilite', ChoiceType::class, [
                'choices' => [
                    'Public' => 'public',
                    'Privé' => 'prive',
                ],
                'label' => 'Visibilité',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'Choisissez la visibilité de votre portfolio',
                'required' => true,
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
