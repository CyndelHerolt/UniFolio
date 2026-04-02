<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\DupliquerTrait;
use App\Entity\TypeGroupe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeGroupeCrudController extends AbstractCrudController
{
    use DupliquerTrait;

    public static function getEntityFqcn(): string
    {
        return TypeGroupe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            BooleanField::new('mutualise'),
            IntegerField::new('ordre_semestre'),
            TextField::new('type'),
            AssociationField::new('semestre'),
        ];
    }
}
