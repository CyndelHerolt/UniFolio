<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantPartialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
$builder
//    ->add('prenom')
//    ->add('nom')
    ->add('mail_perso')
    ->add('telephone')
//    ->add('mail_univ')
    ->add('bio')
//    ->add('groupe')
//    ->add('annee_universitaire')
//    ->add('users')
;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
