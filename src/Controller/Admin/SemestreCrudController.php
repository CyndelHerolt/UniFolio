<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller\Admin;

use App\Entity\Semestre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SemestreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Semestre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            TextField::new('code_element'),
            IntegerField::new('ordre_annee'),
            IntegerField::new('nb_groupes_cm'),
            IntegerField::new('nb_groupes_td'),
            IntegerField::new('nb_groupes_tp'),
            IntegerField::new('ordre_lmd'),
            BooleanField::new('actif'),
            AssociationField::new('annee'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Semestre')
            ->setEntityLabelInPlural('Semestres');
    }
}
