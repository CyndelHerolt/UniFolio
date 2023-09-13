<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * * @deprecated
 */
class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('date_creation')
//            ->add('date_modification')
            ->add('contenu', TextareaType::class, [
                'label'    => 'Ajouter un commentaire',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control',
                ],
            ])
            ->add('visibilite', ChoiceType::class, [
                'label'    => 'Visibilité',
                'required' => true,
                'attr'     => [
                    'class' => 'switch',
                ],
                'choices'  => [
                    'Public'   => true,
                    'Privé' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                // cocher par défaut
                'data'     => true,
                'mapped'   => true,
            ])
//            ->add('portfolio')
//            ->add('trace')
//            ->add('enseignant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
