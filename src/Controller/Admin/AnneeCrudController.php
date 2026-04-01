<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnneeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annee::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            TextField::new('libelle_long'),
            IntegerField::new('ordre'),
            BooleanField::new('opt_alternance'),
            BooleanField::new('actif'),
            AssociationField::new('diplome'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Année')
            ->setEntityLabelInPlural('Années');
    }
}
