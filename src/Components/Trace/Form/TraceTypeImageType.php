<?php

namespace App\Components\Trace\Form;

use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Entity\Trace;
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

class TraceTypeImageType extends AbstractType
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
                'label' => 'Titre',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => 'Titre de ma trace',],
                'help' => '100 caractères maximum',
            ])
            ->add('contenu', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'formats acceptés : jpg, jpeg, png, gif, svg, webp',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control", 'placeholder' => '...'],
                'help' => 'Commentez votre trace pour justifier sa pertinence',
            ])
            ->add('enregistrer', SubmitType::class, [
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