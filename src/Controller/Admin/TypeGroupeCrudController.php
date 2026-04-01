<?php

namespace App\Controller\Admin;

use App\Entity\TypeGroupe;
use App\Repository\TypeGroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeGroupeCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TypeGroupeRepository $typeGroupeRepository
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return TypeGroupe::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate', 'Dupliquer')
            ->linkToCrudAction('duplicateEntity')
            ->setIcon('fa-solid fa-copy')
        ;

        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, $duplicate)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('libelle'),
            BooleanField::new('mutualise'),
            IntegerField::new('ordre_semestre'),
            TextField::new('type'),
            AssociationField::new('semestre'),
        ];
    }

    public function duplicateEntity(AdminContext $context)
    {
        $typeGroupe = $context->getEntity()->getInstance();
        // on récupère le plus grand id de typeGroupe
        $maxId = $this->typeGroupeRepository->findMaxId();

        // on duplique le typeGroupe
        $newTypeGroupe = clone $typeGroupe;
        $newTypeGroupe->setId($maxId + 1);
        $newTypeGroupe->setLibelle($typeGroupe->getLibelle() . ' (copie)');
        $newTypeGroupe->setMutualise($typeGroupe->isMutualise());
        $newTypeGroupe->setOrdreSemestre($typeGroupe->getOrdreSemestre());
        $newTypeGroupe->setType($typeGroupe->getType());
        foreach ($typeGroupe->getSemestre() as $semestre) {
            $newTypeGroupe->addSemestre($semestre);
        }

        $this->entityManager->persist($newTypeGroupe);
        $this->entityManager->flush();

        // rediriger sur la page de la liste
        return $this->redirect('admin?crudAction=index&crudControllerFqcn=App\Controller\Admin\TypeGroupeCrudController');
    }
}
