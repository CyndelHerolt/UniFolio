<?php
namespace App\Controller\Admin\Trait;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Doctrine\ORM\EntityManagerInterface;

trait DupliquerTrait
{
    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate', 'Dupliquer')
            ->linkToCrudAction('duplicateEntity')
            ->setIcon('fa-solid fa-copy')
            ->displayIf(fn ($entity) => true);

        return $actions->add(Crud::PAGE_INDEX, $duplicate);
    }

    /**
     * Méthode publique pour la duplication (appelée par EasyAdmin).
     */
    public function duplicateEntity(AdminContext $context, EntityManagerInterface $entityManager)
    {
        $newEntity = $this->duplicateEntityBase($context, $entityManager);

// Persistance de l'entité clonée
        $entityManager->persist($newEntity);
        $entityManager->flush();

        return $this->redirectToRoute('admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => $context->getCrud()->getControllerFqcn(),
        ]);
    }

    /**
     * Méthode protégée pour le clonage (logique générique).
     */
    protected function duplicateEntityBase(AdminContext $context, EntityManagerInterface $entityManager)
    {
        $entity = $context->getEntity()->getInstance();
        $newEntity = clone $entity;
        // récupérer le repository de l'entité
        $repository = $entityManager->getRepository($entity::class);
        $maxId = $repository->findMaxId();
        // Logique générique (ex: modifier le libellé)
        if (method_exists($newEntity, 'setLibelle')) {
            $newEntity->setLibelle($entity->getLibelle() . ' (copie)');
        }
        $newEntity->setId($maxId + 1);

        return $newEntity;
    }
}
