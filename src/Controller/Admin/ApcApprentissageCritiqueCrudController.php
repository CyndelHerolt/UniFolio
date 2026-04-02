<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Trait\DupliquerTrait;
use App\Entity\ApcApprentissageCritique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApcApprentissageCritiqueCrudController extends AbstractCrudController
{
    use DUpliquerTrait;
    public static function getEntityFqcn(): string
    {
        return ApcApprentissageCritique::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextEditorField::new('libelle'),
            TextField::new('code'),
            AssociationField::new('niveaux'),
        ];
    }
}
