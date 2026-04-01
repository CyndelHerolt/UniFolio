<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EtudiantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etudiant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('username'),
            TextField::new('prenom'),
            TextField::new('nom'),
            EmailField::new('mail_perso'),
            EmailField::new('mail_univ'),
            TextField::new('telephone'),
            TextEditorField::new('bio'),
            BooleanField::new('opt_alternance'),
            BooleanField::new('opt_stage'),
            IntegerField::new('annee_sortie'),
            ChoiceField::new('users?.roles', 'Roles')->setChoices([
                'Etudiant' => 'ROLE_ETUDIANT',
                'Enseignant' => 'ROLE_ENSEIGNANT',
                'Admin' => 'ROLE_ADMIN',
            ])->allowMultipleChoices(),
            BooleanField::new('users?.is_verified', 'Vérifié'),
            AssociationField::new('groupe'),
        ];
    }
}
