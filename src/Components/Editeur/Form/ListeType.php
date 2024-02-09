<?php

namespace App\Components\Editeur\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;

class ListeType extends ElementType
{
    public $colonne;
    public $contenu;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('contenu', CollectionType::class, [
                'entry_type' => TextType::class,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Vous devez ajouter au moins un élément dans votre liste ou la supprimer'
                    ])
                ],
                'entry_options' => [
                    'attr' => [
                        'class' => "form-control liste_element",
                        'placeholder' => 'Liste',
                    ],
                    'by_reference' => false,
                    'label' => false,
                ],
                'prototype' => true,
                'label' => false,
                'allow_extra_fields' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'empty_data' => [],
                'mapped' => true,
            ]);
    }
}