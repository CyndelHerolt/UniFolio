<?php

namespace App\Components\Editeur\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends ElementType
{
    public $colonne;
    public $contenu;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('contenu', FileType::class, [
                'label' => 'Contenu de l\'image',
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