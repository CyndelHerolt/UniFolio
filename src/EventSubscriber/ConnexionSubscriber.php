<?php

namespace App\EventSubscriber;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Security\AccountNotVerifiedAuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class ConnexionSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly DepartementRepository $departementRepository, private readonly RequestStack $session, private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {

        $passport = $event->getPassport();
        $user = $passport->getUser();
        if ($user instanceof Etudiant) {
            $departement = $this->departementRepository->findDepartementEtudiant($user);
            $this->session->getSession()->set('departement', $departement);
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        }
        elseif ($user instanceof Enseignant) {
            $departement = $this->departementRepository->findDepartementEnseignant($user);
            $this->session->getSession()->set('departement', $departement);
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -11],];
    }
}