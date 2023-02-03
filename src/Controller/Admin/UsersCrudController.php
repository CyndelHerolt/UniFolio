<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            TextField::new('username'),
//            ChoiceField::new('roles'),
//            TextField::new('prenom'),
//            TextField::new('nom'),
//            EmailField::new('mail'),
//            EmailField::new('mail_univ'),
//        ];
//    }
}
