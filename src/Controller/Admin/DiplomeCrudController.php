<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\DupliquerTrait;
use App\Entity\Diplome;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DiplomeCrudController extends AbstractCrudController
{
    use DupliquerTrait;

    public static function getEntityFqcn(): string
    {
        return Diplome::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('sigle'),
            TextField::new('libelle'),
            AssociationField::new('departement'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Diplome')
            ->setEntityLabelInPlural('Diplomes');
    }
}
