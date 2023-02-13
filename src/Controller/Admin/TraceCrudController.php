<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Trace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TraceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trace::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
//            IdField::new('id'),
            TextField::new('auteur'),
            TextField::new('titre'),
            DateField::new('date_creation'),
            DateField::new('date_modification'),
//            TextEditorField::new('description'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commentaire')
            ->setEntityLabelInPlural('Commentaires')
            ->setSearchFields(['auteur', 'destinataire', 'date_creation', 'date_modification'])
            ->setDefaultSort(['date_creation' => 'DESC']);
    }
}
