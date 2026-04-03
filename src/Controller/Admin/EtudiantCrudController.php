<?php
namespace App\Controller\Admin;

use App\Entity\Etudiant;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
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

    public function createEntity(string $entityFqcn)
    {
        $etudiant = new Etudiant();
// On crée un user vide qui sera configuré par l'EventSubscriber
        $etudiant->setUsers(new Users());
        return $etudiant;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            TextField::new('users.password', 'Mot de passe'),
            TextField::new('prenom'),
            TextField::new('nom'),
            EmailField::new('mail_perso'),
            EmailField::new('mail_univ'),
            TextField::new('telephone'),
            TextEditorField::new('bio'),
            BooleanField::new('opt_alternance'),
            BooleanField::new('opt_stage'),
            IntegerField::new('annee_sortie'),
            AssociationField::new('groupe'),

// Champs pour l'utilisateur
            ChoiceField::new('users.roles', 'Rôles')
                ->setChoices([
                    'Etudiant' => 'ROLE_ETUDIANT',
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),
            BooleanField::new('users.is_verified', 'Vérifié'),
        ];
    }
}
