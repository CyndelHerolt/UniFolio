<?php

namespace App\Components\Editeur\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colonne', ChoiceType::class,
            [
                'label' => 'label.colonne',
                'choices' => $options['listeColonne'],
                'attr' => ['data-action' => 'questionnaire--question#changeTypeQuestion']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ElementType::class,
            'listeColonne' => [],
        ]);
    }
}