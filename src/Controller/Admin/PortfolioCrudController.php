<?php

namespace App\Controller\Admin;

use App\Entity\Portfolio;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PortfolioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Portfolio::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
//            IdField::new('id'),
            TextField::new('intitule'),
//            TextEditorField::new('description'),
        ];
    }

}
