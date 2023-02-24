<?php

namespace App\Controller\Admin;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Form\EtudiantType;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};

class UsersCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Construction manuelle des input étudiant
        $etudiant = FormField::addPanel('Etudiant')
            ->setFormTypeOption('data_class', Etudiant::class)
            ->setFormType(EtudiantType::class)
            ->onlyOnForms();

        // Construction manuelle des input de mot de passe
        $password = TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
                'mapped' => false,
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();

        return [

            TextField::new('username', 'Nom d\'utilisateur'),
            ChoiceField::new('roles', 'Rôles')->setHelp('Rôles disponibles: ROLE_ADMIN, ROLE_ETUDIANT, ROLE_ENSEIGNANT')->allowMultipleChoices(true)->setChoices([
                'etudiant' => 'ROLE_ETUDIANT',
                'enseignant' => 'ROLE_ENSEIGNANT',
                'admin' => 'ROLE_ADMIN',
            ]),
            EmailField::new('email', 'Adresse mail'),
            $password,
            $etudiant,
        ];
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    //
    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword()
    {
        return function ($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }

    //-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------

    private function newEtudiant(EtudiantRepository $etudiantRepository, Users $user){
        if (in_array('ROLE_ETUDIANT', $user->getRoles())){
            $etudiant = new Etudiant();
            $etudiant->setUsers($user);
//            $etudiantRepository->save($etudiant, true);
        }
    }
 private function newEnseignant(EnseignantRepository $enseignantRepository, Users $user){
        if (in_array('ROLE_ENSEIGNANT', $user->getRoles())){
            $enseignant = new Enseignant();
            $enseignant->setUsers($user);
//            $enseignantRepository->save($enseignant, true);
        }
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->newEtudiant($entityManager->getRepository(Etudiant::class), $entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
        $this->newEnseignant($entityManager->getRepository(Enseignant::class), $entityInstance);

        // Si l'objet créé est un User, on doit créer un objet Etudiant lié
        if ($entityInstance instanceof Users) {
            $etudiant = new Etudiant();
            $etudiant->setUsers($entityInstance);
            // Récupérer les données saisies dans le formulaire et les affecter à l'objet Etudiant
            $form = $this->createForm(EtudiantType::class, $etudiant);
            if ($form->isSubmitted() && $form->isValid()) {
            $form->handleRequest(Request::createFromGlobals());
                $entityManager->persist($etudiant);
                $entityManager->flush(true);
            }
        }
//        var_dump(Request::CreateFromGlobals());
//        die();


        // Appel à la méthode parente pour enregistrer l'objet User
        parent::persistEntity($entityManager, $entityInstance);
    }


//    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
//    {
//        // Si l'instance actuelle est une entité Users
//        if ($entityInstance instanceof Users) {
//            // On récupère l'entité Etudiant depuis l'instance de Users
//            $etudiant = $entityInstance->getEtudiant();
//
//            // Si l'entité Etudiant n'existe pas encore
//            if ($etudiant === null) {
//                // On crée une nouvelle instance de Etudiant
//                $etudiant = new Etudiant();
//
//                // On set les informations nécessaires (exemple)
//                $etudiant->setPrenom('prenom');
//
//
//                // On associe l'entité Etudiant à l'entité Users
//                $entityInstance->setEtudiant($etudiant);
//            }
//
//            // On persiste l'entité Etudiant
//            $entityManager->persist($etudiant);
//        }
//
//        // On persiste l'entité Users
//        $entityManager->persist($entityInstance);
//
//        // On exécute le flush
//        $entityManager->flush();
//    }
////-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->showEntityActionsInlined();

//            ->setSearchFields(['username', 'roles']);
    }

    private function getDoctrine()
    {

    }
}