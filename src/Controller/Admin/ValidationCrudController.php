<?php

namespace App\Controller\Admin;

use App\Entity\Validation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ValidationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Validation::class;
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
}
