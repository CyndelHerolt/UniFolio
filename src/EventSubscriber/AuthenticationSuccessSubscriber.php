<?php

namespace App\EventSubscriber;

use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * @deprecated
 */
class AuthenticationSuccessSubscriber extends AbstractController implements EventSubscriberInterface
{
    public function __construct(
        private readonly DepartementRepository $departementRepository,
        private readonly RequestStack          $session,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly EtudiantRepository    $etudiantRepository,
        private readonly EnseignantRepository  $enseignantRepository,
    )
    {
    }

    public function onSecurityAuthenticationSuccess(AuthenticationEvent $event): RedirectResponse
    {

//        dd($event);
//        $passport = $event->getPassport();
//        $user = $passport->getUser();

        $user = $event->getAuthenticationToken()->getUser();
 //       $user = $event->getUser();

        $etudiant = $this->etudiantRepository->findOneBy(['username' => $user->getUsername()]);
        $enseignant = $this->enseignantRepository->findOneBy(['id' => $user->getUsername()]);

        if ($etudiant) {
            $departement = $this->departementRepository->findDepartementEtudiant($etudiant);
//            dd($departement);
            $this->session->getSession()->set('departement', $departement);
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        }

        if ($enseignant) {

            $departements = $this->departementRepository->findDepartementEnseignant($enseignant);

            if (0 === count($departements)) {
                $this->addFlash('danger', 'Vous n\'êtes pas affecté à un département, veuillez contacter l\'administrateur');
                return new RedirectResponse($this->urlGenerator->generate('app_login'));
            }

            $departement = $this->departementRepository->findDepartementEnseignantDefaut($enseignant);
dump($departement);
            if (0 === count($departement)) {
            dump($event);
            dump($this->urlGenerator->generate('app_choix_departement', [], UrlGeneratorInterface::ABSOLUTE_URL));
           // dd('fin');
                return new RedirectResponse($this->urlGenerator->generate('app_choix_departement', [], UrlGeneratorInterface::ABSOLUTE_URL));
            }

            if (1 === count($departement)) {
                $this->session->getSession()->set('departement', $departement);
                return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
            }
        }
        $this->addFlash('danger', 'Vous n\'êtes pas affecté à un département, veuillez contacter l\'administrateur');
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    public static function getSubscribedEvents() : array
    {
        return [
//            CheckPassportEvent::class => ['onCheckPassport', -20],
            'security.authentication.success' => ['onSecurityAuthenticationSuccess',2000]

        ];
    }
}