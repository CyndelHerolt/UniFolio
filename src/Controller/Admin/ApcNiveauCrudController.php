<?php

namespace App\Controller\Admin;

use App\Entity\ApcNiveau;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApcNiveauCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApcNiveau::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            IntegerField::new('ordre'),
            AssociationField::new('annees'),
            AssociationField::new('competences'),
            AssociationField::new('apcParcours'),
        ];
    }
}
