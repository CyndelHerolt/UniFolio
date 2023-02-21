<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EtudiantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etudiant::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

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
//            ->setSearchFields(['username', 'roles']);
    }
}
