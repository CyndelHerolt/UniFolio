<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use App\Entity\Users;
use App\Form\UsersType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Faker\Provider\Text;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EtudiantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etudiant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Informations Etudiant')->setIcon('fa fa-user');
        yield TextField::new('prenom', 'Prénom')
            ->setFormTypeOption('required', 'true');
        yield TextField::new('nom', 'Nom')
            ->setFormTypeOption('required', 'true');
        yield TextField::new('mail_perso', 'Mail personnel')
            ->setFormType(EmailType::class);
        yield TextField::new('mail_univ', 'Mail universitaire')
            ->setFormTypeOption('required', 'true')
            ->setFormType(EmailType::class);
        yield TextField::new('telephone', 'Téléphone');
        yield TextField::new('bio', 'Bio');

        //todo: ajouter les champs de l'utilisateur et gérer l'envoi en db

//        yield FormField::addPanel('Informations Utilisateur')->setIcon('fa fa-user');
//        yield 'user' => FormField::addPanel('Utilisateur')
//            ->setFormType(UsersType::class)
//            ->setFormTypeOptions([
////                'required' => 'true',
//            ]);
//        // Ajoutez le formulaire UsersType ici
//        yield ChoiceField::new('roles')
//            ->setChoices(['ROLE_ETUDIANT' => 'ROLE_ETUDIANT', 'ROLE_ADMIN' => 'ROLE_ADMIN'])
//            ->allowMultipleChoices()
//            ->setRequired(true)
//            ->setFormTypeOption('mapped', false)
//            ->setFormTypeOption('label', 'Rôles')
//            ->setFormTypeOption('help', 'Choisissez le rôle de l\'utilisateur')
//            ->setFormTypeOption('attr', ['class' => 'form-control'])
//            ->setFormTypeOption('choice_attr', ['class' => 'form-check-inline']);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Etudiant.e')
            ->setEntityLabelInPlural('Etudiant.es')
            ->showEntityActionsInlined()
            ->setPaginatorPageSize(20)
            ->setPageTitle('index', 'Liste des étudiant.es')
            ->setPageTitle('new', 'Ajouter un.e étudiant.e')
            ->setPageTitle('edit', 'Modifier un.e étudiant.e');
    }

//    public function createEntity(string $entityFqcn)
//    {
//        $etudiant = new Etudiant();
//        $user = new Users();
//        $etudiant->setUsers($user);
//
//        return $etudiant;
//    }
}
