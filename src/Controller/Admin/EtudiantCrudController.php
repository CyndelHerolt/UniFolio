<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EtudiantCrudController extends AbstractCrudController
{

    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Etudiant::class;
    }

    public function configureFields(string $pageName): iterable
    {

        // Etudiant
        yield FormField::addPanel('Informations Etudiant')->setIcon('fa fa-user');
        yield TextField::new('prenom', 'Prénom')
            ->setFormTypeOption('required', 'true');
        yield TextField::new('nom', 'Nom')
            ->setFormTypeOption('required', 'true');
        yield TextField::new('mail_perso', 'Mail personnel')
            ->setFormType(EmailType::class);
        yield TextField::new('mail_univ', 'Mail universitaire')
            ->setFormTypeOption('required', 'true')
            ->setFormType(EmailType::class);
        yield TextField::new('telephone', 'Téléphone');

        //User
            yield TextField::new('username');
        //Si on se trouve sur la page new
        if ($pageName == Crud::PAGE_NEW) {
            yield TextField::new('password')
                ->setFormTypeOption('mapped', false);

            yield ChoiceField::new('roles')
                ->setChoices(['ROLE_ETUDIANT' => 'ROLE_ETUDIANT', 'ROLE_ADMIN' => 'ROLE_ADMIN'])
                ->allowMultipleChoices()
                ->setRequired(true)
                ->setFormTypeOption('mapped', false)
                ->setFormTypeOption('label', 'Rôles')
                ->setFormTypeOption('help', 'Choisissez le rôle de l\'utilisateur')
                ->setFormTypeOption('attr', ['class' => 'form-control'])
                ->setFormTypeOption('choice_attr', ['class' => 'form-check-inline']);
        }
    }

        public
        function configureCrud(Crud $crud): Crud
        {
            return $crud
                ->setEntityLabelInSingular('Etudiant.e')
                ->setEntityLabelInPlural('Etudiant.es')
                ->showEntityActionsInlined()
                ->setPaginatorPageSize(20)
                ->setPageTitle('index', 'Liste des étudiant.es')
                ->setPageTitle('new', 'Ajouter un.e étudiant.e')
                ->setPageTitle('edit', 'Modifier un.e étudiant.e');
        }

        public
        function new(AdminContext $context)
    {
        $event = new BeforeCrudActionEvent($context);
        $this->container->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        if (!$this->isGranted(Permission::EA_EXECUTE_ACTION, ['action' => Action::NEW, 'entity' => null])) {
            throw new ForbiddenActionException($context);
        }

        if (!$context->getEntity()->isAccessible()) {
            throw new InsufficientEntityPermissionException($context);
        }

        $context->getEntity()->setInstance($this->createEntity($context->getEntity()->getFqcn()));
        $this->container->get(EntityFactory::class)->processFields($context->getEntity(), FieldCollection::new($this->configureFields(Crud::PAGE_NEW)));
        $context->getCrud()->setFieldAssets($this->getFieldAssets($context->getEntity()->getFields()));
        $this->container->get(EntityFactory::class)->processActions($context->getEntity(), $context->getCrud()->getActionsConfig());

        $newForm = $this->createNewForm($context->getEntity(), $context->getCrud()->getNewFormOptions(), $context);
        $newForm->handleRequest($context->getRequest());

        $entityInstance = $newForm->getData();
        $context->getEntity()->setInstance($entityInstance);

        if ($newForm->isSubmitted() && $newForm->isValid()) {

            $this->processUploadedFiles($newForm);

            $event = new BeforeEntityPersistedEvent($entityInstance);
            $this->container->get('event_dispatcher')->dispatch($event);
            $entityInstance = $event->getEntityInstance();

            $user = new Users();

            // password hashing
            $plaintextPassword = $context->getRequest()->request->all('Etudiant')['password'];

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );


            $user->setUsername($context->getRequest()->request->all('Etudiant')['username']);
            $user->setPassword($hashedPassword);
            $user->setRoles($context->getRequest()->request->all('Etudiant')['roles']);
            $user->setIsVerified(true);

            $etudiant = new Etudiant();
            $etudiant->setUsername($context->getRequest()->request->all('Etudiant')['username']);
            $etudiant->setPrenom($context->getRequest()->request->all('Etudiant')['prenom']);
            $etudiant->setNom($context->getRequest()->request->all('Etudiant')['nom']);
            $etudiant->setMailPerso($context->getRequest()->request->all('Etudiant')['mail_perso']);
            $etudiant->setMailUniv($context->getRequest()->request->all('Etudiant')['mail_univ']);
            $etudiant->setTelephone($context->getRequest()->request->all('Etudiant')['telephone']);

            $user->setEtudiant($etudiant);

            $usersRepository = $this->entityManager->getRepository(Users::class);
            $etudiantRepository = $this->entityManager->getRepository(Etudiant::class);

            $usersRepository->save($user, true);
            $etudiantRepository->save($etudiant, true);

            $this->container->get('event_dispatcher')->dispatch(new AfterEntityPersistedEvent($entityInstance));
            $context->getEntity()->setInstance($entityInstance);

            return $this->getRedirectResponseAfterSave($context, Action::NEW);
        }

        $responseParameters = $this->configureResponseParameters(KeyValueStore::new([
            'pageName' => Crud::PAGE_NEW,
            'templateName' => 'crud/new',
            'entity' => $context->getEntity(),
            'new_form' => $newForm,
        ]));

        $event = new AfterCrudActionEvent($context, $responseParameters);
        $this->container->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        return $responseParameters;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {


    }
}
