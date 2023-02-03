<?php

namespace App\Controller\Admin;

use App\Entity\Trace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TraceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trace::class;
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
