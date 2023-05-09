<?php

namespace App\EventSubscriber;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckUserDeptSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly DepartementRepository $departementRepository,
        private readonly RequestStack $session,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly EtudiantRepository $etudiantRepository,
        private readonly EnseignantRepository $enseignantRepository
    )
    {
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
//        dd($event);
        $passport = $event->getPassport();
        $user = $passport->getUser();

        $etudiant = $this->etudiantRepository->findOneBy(['username' => $user->getUsername()]);
        $enseignant = $this->enseignantRepository->findOneBy(['username' => $user->getUsername()]);

        if ($etudiant) {
            $departement = $this->departementRepository->findDepartementEtudiant($etudiant);
//            dd($departement);
            $this->session->getSession()->set('departement', $departement);
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        }

        if ($enseignant) {
//            dd($user);
            $departements = $this->departementRepository->findDepartementEnseignant($enseignant);

            if (0 === count($departements)) {
                return new RedirectResponse($this->urlGenerator->generate('app_login',
                    ['message' => 'Vous n\'êtes pas affecté à un département, veuillez contacter l\'administrateur']));
            }

            $departement = $this->departementRepository->findDepartementEnseignantDefaut($enseignant);

            if (0 === count($departement)) {
                return new RedirectResponse($this->urlGenerator->generate('app_choix_departement'));
            }

            if (1 === count($departement)) {
                $this->session->getSession()->set('departement', $departement);
                return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
            }
        }
            return new RedirectResponse($this->urlGenerator->generate('app_login',
                ['message' => 'Vous n\'êtes pas affecté à un département, veuillez contacter l\'administrateur']));
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -20],];
    }
}