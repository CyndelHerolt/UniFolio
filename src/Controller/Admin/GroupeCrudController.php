<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller\Admin;

use App\Controller\Admin\Trait\DupliquerTrait;
use App\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupeCrudController extends AbstractCrudController
{
    use DupliquerTrait;

    public static function getEntityFqcn(): string
    {
        return Groupe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            TextField::new('code_apogee'),
            IntegerField::new('ordre'),
            AssociationField::new('type_groupe'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Groupe')
            ->setEntityLabelInPlural('Groupes');
    }
}
