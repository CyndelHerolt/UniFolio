<?php

namespace App\Components\Editeur\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TitreType extends ElementType
{
    public $colonne;
    public $contenu;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('contenu', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        parent::configureOptions($resolver);
//    }
}