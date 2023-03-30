<?php

namespace App\Controller\Admin;

use App\Repository\TraceRepository;
use App\Repository\BibliothequeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Trace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
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
            AssociationField::new('bibliotheque')->hideOnForm(),
            TextField::new('titre'),
            ChoiceField::new('type_trace', 'Type de trace')->setChoices([
                'image' => 'App\Components\Trace\TypeTrace\TraceTypeImage',
                'lien' => 'App\Components\Trace\TypeTrace\TraceTypeLien',
                'video' => 'App\Components\Trace\TypeTrace\TraceTypeVideo',
                'pdf' => 'App\Components\Trace\TypeTrace\TraceTypePdf',
            ]),
            TextEditorField::new('description'),
            ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Trace')
            ->setEntityLabelInPlural('Traces');
    }
}
