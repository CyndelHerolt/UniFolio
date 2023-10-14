<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller\Admin;

use App\Entity\AnneeUniversitaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnneeUniversitaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnneeUniversitaire::class;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Année Universitaire')
            ->setEntityLabelInPlural('Années Universitaires');
    }
}
