<?php
namespace App\EventSubscriber;

use App\Entity\Bibliotheque;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Repository\SemestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
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
            AfterEntityPersistedEvent::class => ['onAfterEntityPersisted'],
            AfterEntityUpdatedEvent::class => ['onAfterEntityUpdated'],
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

        $user = $entity->getUsers();
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    public function onAfterEntityPersisted(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Etudiant) {
            return;
        }

        $this->createBibliothequeIfNotExists($entity);
    }

    public function onAfterEntityUpdated(AfterEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Etudiant) {
            return;
        }

        $this->createBibliothequeIfNotExists($entity);
    }

    private function configureUserForEtudiant(Etudiant $etudiant): void
    {
        $user = $etudiant->getUsers();

        if (null === $user) {
            $user = new Users();
            $etudiant->setUsers($user);
        }

        $user->setUsername($etudiant->getUsername());
        $user->setEmail($etudiant->getMailPerso() ?? $etudiant->getMailUniv());

        $plainPassword = $user->getPassword() ?: Uuid::v4()->toRfc4122();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $groupes = $etudiant->getGroupe();
        $groupe = $groupes[0];
        if ($groupe) {
            $semestre = $this->semestreRepository->findSemestreGroupe($groupe);
            if ($semestre) {
                $etudiant->setSemestre($semestre[0]);
            }
        }
    }

    private function createBibliothequeIfNotExists(Etudiant $etudiant): void
    {
        // Si l'étudiant a déjà une bibliothèque, on ne fait rien
        if (!$etudiant->getBibliotheques()->isEmpty()) {
            return;
        }

        // Récupérer l'année via le semestre de l'étudiant
        $annee = $etudiant->getSemestre()?->getAnnee();

        $bibliotheque = new Bibliotheque();
        $bibliotheque->setEtudiant($etudiant);
        $bibliotheque->setAnnee($annee);

        $this->entityManager->persist($bibliotheque);
        $this->entityManager->flush();
    }
}
