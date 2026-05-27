<?php

namespace App\Controller\Admin;

use App\Entity\Enseignant;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EnseignantCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Enseignant::class;
    }

    // +++ Abonnement aux événements EasyAdmin au lieu de surcharger new() et edit()
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdated',
        ];
    }

    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Enseignant) {
            return;
        }

        // Récupération de la requête courante
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $data = $request->request->all('Enseignant');

        $user = new Users();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);

        $user->setUsername($data['username']);
        $user->setPassword($hashedPassword);
        $user->setRoles($data['roles'] ?? []);
        $user->setIsVerified(true);
        $user->setEnseignant($entity);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function onBeforeEntityUpdated(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Enseignant) {
            return;
        }

        $request = $this->container->get('request_stack')->getCurrentRequest();
        $data = $request->request->all('Enseignant');

        $user = $this->entityManager->getRepository(Users::class)
            ->findOneBy(['username' => $data['username']]);

        if ($user) {
            $user->setUsername($data['username']);
            $user->setRoles($data['roles'] ?? []);
            $this->entityManager->persist($user);
        }
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Informations Enseignant')->setIcon('fa fa-user');
        yield TextField::new('prenom', 'Prénom')
            ->setFormTypeOption('required', true);
        yield TextField::new('nom', 'Nom')
            ->setFormTypeOption('required', true);
        yield TextField::new('mail_perso', 'Mail personnel')
            ->setFormType(EmailType::class);
        yield TextField::new('mail_univ', 'Mail universitaire')
            ->setFormTypeOption('required', true)
            ->setFormType(EmailType::class);
        yield TextField::new('telephone', 'Téléphone');
        yield TextField::new('username')
            ->setFormTypeOption('required', true);

        if ($pageName === Crud::PAGE_NEW) {
            yield TextField::new('password')
                ->setFormTypeOption('mapped', false)
                ->setFormTypeOption('required', true);
        }

        yield ChoiceField::new('roles')
            ->setChoices([
                'ENSEIGNANT' => 'ROLE_ENSEIGNANT',
                'ADMIN' => 'ROLE_ADMIN',
                'TEST' => 'ROLE_TEST'
            ])
            ->allowMultipleChoices()
            ->setRequired(true)
            ->setFormTypeOption('mapped', false)
            ->setFormTypeOption('label', 'Rôles')
            ->setFormTypeOption('help', 'Veuillez sélectionner tous les rôles qui s\'appliquent à cet utilisateur.')
            ->setFormTypeOption('attr', ['class' => 'form-control'])
            ->setFormTypeOption('choice_attr', ['class' => 'form-check-inline']);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Enseignant.e')
            ->setEntityLabelInPlural('Enseignant.es')
            ->showEntityActionsInlined()
            ->setPaginatorPageSize(20)
            ->setPageTitle('index', 'Liste des enseignant.es')
            ->setPageTitle('new', 'Ajouter un.e enseignant.e')
            ->setPageTitle('edit', 'Modifier un.e enseignant.e');
    }

    // +++ On laisse EasyAdmin gérer la persistance de l'Enseignant
    // mais on empêche la double persistance
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
