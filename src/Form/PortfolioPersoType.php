<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Form;

use App\Entity\PortfolioPerso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioPersoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé du portfolio',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('visibilite', ChoiceType::class, [
                'choices' => [
                    'Public' => true,
                    'Privé' => false,
                ],
                'label' => 'Visibilité',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => "form-control"],
                'help' => 'Choisissez la visibilité de votre portfolio',
                'required' => true,
                'mapped' => true,
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PortfolioPerso::class,
        ]);
    }
}
