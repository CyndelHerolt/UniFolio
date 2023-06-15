<?php

namespace App\Controller\Admin;

use App\Entity\Enseignant;
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EnseignantCrudController extends AbstractCrudController
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
        return Enseignant::class;
    }

    public function configureFields(string $pageName): iterable
    {

        // Enseignant
        yield FormField::addPanel('Informations Enseignant')->setIcon('fa fa-user');
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
                ->setChoices(['ENSEIGNANT' => 'ROLE_ENSEIGNANT', 'ADMIN' => 'ROLE_ADMIN'])
                ->allowMultipleChoices()
                ->setRequired(true)
                ->setFormTypeOption('mapped', false)
                ->setFormTypeOption('label', 'Rôles')
                ->setFormTypeOption('help', 'Choisissez le rôle de l\'utilisateur')
                ->setFormTypeOption('attr', ['class' => 'form-control'])
                ->setFormTypeOption('choice_attr', ['class' => 'form-check-inline']);
        }
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

    public function new(AdminContext $context) : Response
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
            $plaintextPassword = $context->getRequest()->request->all('Enseignant')['password'];

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );


            $user->setUsername($context->getRequest()->request->all('Enseignant')['username']);
            $user->setPassword($hashedPassword);
            $user->setRoles($context->getRequest()->request->all('Enseignant')['roles']);
            $user->setIsVerified(true);

            $enseignant = new Enseignant();
            $enseignant->setUsername($context->getRequest()->request->all('Enseignant')['username']);
            $enseignant->setPrenom($context->getRequest()->request->all('Enseignant')['prenom']);
            $enseignant->setNom($context->getRequest()->request->all('Enseignant')['nom']);
            $enseignant->setMailPerso($context->getRequest()->request->all('Enseignant')['mail_perso']);
            $enseignant->setMailUniv($context->getRequest()->request->all('Enseignant')['mail_univ']);
            $enseignant->setTelephone($context->getRequest()->request->all('Enseignant')['telephone']);

            $user->setEnseignant($enseignant);

            $usersRepository = $this->entityManager->getRepository(Users::class);
            $enseignantRepository = $this->entityManager->getRepository(Enseignant::class);

            $usersRepository->save($user, true);
            $enseignantRepository->save($enseignant, true);

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
