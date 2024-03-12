<?php

namespace App\Components\Editeur\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ParagrapheType extends ElementType
{
    public $colonne;
    public $contenu;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Paragraphe',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3, 'data-model' => 'elementContent', 'data-action' => 'input'
                ],
            ]);
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        parent::configureOptions($resolver);
//    }
}