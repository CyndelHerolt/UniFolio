<?php

namespace App\Components\Editeur\Form;

use App\Components\Editeur\Form\ElementType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagrapheType extends ElementType
{
    public $colonne;
    public $contenu;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu du paragraphe',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                ],
            ]);
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        parent::configureOptions($resolver);
//    }
}