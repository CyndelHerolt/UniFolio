<?php

namespace App\Components\Trace\Form;

use App\Entity\Trace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraceTypePdfType extends AbstractType
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
            ->add('date_modification', DateTimeType::class, [
                'data' => new \DateTimeImmutable(),
                'widget' => 'single_text',
                'disabled' => true,
                'label' => ' ',
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => '100 caractères maximum',
            ])
            ->add('contenu', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'format accepté : pdf',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'Commentez votre trace pour justifier sa pertinence',
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
            'data_class' => Trace::class,
        ]);
    }
}
