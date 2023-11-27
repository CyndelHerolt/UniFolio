<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller\Admin;

use App\Entity\Portfolio;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use SebastianBergmann\CodeCoverage\Report\Text;

class PortfolioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Portfolio::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('etudiant')->hideOnForm(),
            TextField::new('intitule'),
            IntegerField::new('visibilite'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Portfolio')
            ->setEntityLabelInPlural('Portfolios')
            ->setSearchFields(['auteur', 'intitule', 'date_creation', 'date_modification'])
            ->setDefaultSort(['date_creation' => 'DESC']);
    }
}
