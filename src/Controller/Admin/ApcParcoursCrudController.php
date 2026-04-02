<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\DupliquerTrait;
use App\Entity\ApcParcours;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApcParcoursCrudController extends AbstractCrudController
{
    use DupliquerTrait;

    public static function getEntityFqcn(): string
    {
        return ApcParcours::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            TextField::new('code'),
            BooleanField::new('actif'),
            BooleanField::new('formation_continue'),
            AssociationField::new('ApcReferentiel'),
            AssociationField::new('diplomes'),
            AssociationField::new('groupes'),
        ];
    }
}
