<?php
namespace App\EventSubscriber;

use App\Entity\Etudiant;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class EtudiantSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['onBeforeEntityPersisted'],
            BeforeEntityUpdatedEvent::class => ['onBeforeEntityUpdated'],
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
    }
}
