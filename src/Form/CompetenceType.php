<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $competences = $options['competences'];

        $builder
            ->add('libelle', ChoiceType::class, [
                'choices' => [$competences],
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                },
                'label' => false,
                'multiple' => true,
                'required' => true,
                'expanded' => true,
                'mapped' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
            'competences' => null,
        ]);
    }
}
