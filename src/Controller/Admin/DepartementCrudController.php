<?php

namespace App\Controller\Admin;

use App\Entity\Departement;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DepartementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Departement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            ImageField::new('logo_name', 'Logo')->setBasePath('uploads/departement/logo')->setUploadDir('public/departement/logo'),
            TelephoneField::new('tel_contact'),
            ColorField::new('couleur'),
            TextEditorField::new('description'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Département')
            ->setEntityLabelInPlural('Départements');
    }
}
