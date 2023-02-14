<?php

namespace App\Controller\Admin;

use App\Entity\Templates;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TemplatesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Templates::class;
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
