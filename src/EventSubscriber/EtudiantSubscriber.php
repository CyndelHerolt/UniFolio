<?php
namespace App\EventSubscriber;

use App\Entity\Etudiant;
use App\Entity\Users;
use App\Repository\SemestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class EtudiantSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private SemestreRepository $semestreRepository,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['onBeforeEntityPersisted'],
            BeforeEntityUpdatedEvent::class => ['onBeforeEntityUpdated'],
            BeforeEntityDeletedEvent::class => ['onBeforeEntityDeleted'],
        ];
    }

    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Etudiant) {
            return;
        }

        $this->configureUserForEtudiant($entity);
    }

    public function onBeforeEntityUpdated(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Etudiant) {
            return;
        }

        $this->configureUserForEtudiant($entity);
    }

    public function onBeforeEntityDeleted(BeforeEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Etudiant) {
            return;
        }

        // supprimer l'utilisateur associé à l'étudiant
        $user = $entity->getUsers();
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    private function configureUserForEtudiant(Etudiant $etudiant): void
    {
        $user = $etudiant->getUsers();

        if (null === $user) {
            $user = new Users();
            $etudiant->setUsers($user);
        }

        // Configuration de base
        $user->setUsername($etudiant->getUsername());
        $user->setEmail($etudiant->getMailPerso() ?? $etudiant->getMailUniv());

        // Seul le mot de passe nécessite un traitement car il faut le hasher
        $plainPassword = $user->getPassword() ?: Uuid::v4()->toRfc4122();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        // récupérer le semestre du groupe
        $groupes = $etudiant->getGroupe();
        $groupe = $groupes[0];
        if ($groupe) {
            $semestre = $this->semestreRepository->findSemestreGroupe($groupe);
            if ($semestre) {
                $etudiant->setSemestre($semestre[0]);
            }
        }
    }
}
