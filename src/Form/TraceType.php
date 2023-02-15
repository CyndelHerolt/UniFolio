<?php

namespace App\Form;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\AbstractTrace;
use App\Entity\Trace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraceType extends AbstractType
{

    public function __construct(
        private TraceRegistry $traceRegistry
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_creation')
            ->add('date_modification')
            ->add('type_trace', ChoiceType::class, [
                'choices' => [
                    $this->traceRegistry->getChoiceTypeTraceName(),
                ],
            ])
            ->add('Créer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trace::class,
        ]);
    }
}
