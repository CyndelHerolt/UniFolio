<?php

namespace App\Form;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\AbstractTrace;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Entity\Trace;
use phpDocumentor\Reflection\DocBlock\Tags\Deprecated;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated
 */
class TraceType extends AbstractType
{

    public function __construct(
        private TraceRegistry $traceRegistry
    )
    {
    }

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
            ->add('type_trace', ChoiceType::class, [
                'choices' => $this->traceRegistry->getChoiceTypeTraceName(),
                'label' => 'Type de trace',
                'expanded' => true,
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

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (isset($data['type_trace']) && $data['type_trace'] === TraceTypeLien::TYPE_TRACE) {
                $form->remove('contenu');
                $form->add('contenu', TextareaType::class, [
                    'required' => true,
                ]);
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trace::class,
        ]);
    }
}
