<?php

namespace App\Controller\Admin;

use App\Entity\ApcReferentiel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApcReferentielCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApcReferentiel::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            TextEditorField::new('description'),
            IntegerField::new('annee_publication'),
            AssociationField::new('departement'),
        ];
    }

}
