<?php

namespace App\Components\Trace\Form;

use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraceTypeVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_creation', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'widget' => 'single_text',
                'disabled' => true,
                'label' => ' ',
            ])
//            ->add('date_modification', DateType::class)
            ->add('titre', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('contenu', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TraceTypeVideo::class,
        ]);
    }
}