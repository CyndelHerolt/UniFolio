<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Security;

use App\Entity\Enseignant;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private readonly DepartementRepository $departementRepository,
        private readonly RequestStack $session,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly EtudiantRepository $etudiantRepository,
        private readonly EnseignantRepository $enseignantRepository,
        private readonly RouterInterface $router
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge(
                    'authenticate',
                    $request->request->get('_csrf_token')
                ),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        $user = $token->getUser();
        $etudiant = $this->etudiantRepository->findOneBy(['username' => $user->getUsername()]);
        $enseignant = $this->enseignantRepository->findOneBy(['username' => $user->getUsername()]);

        if ($etudiant) {
            $departement = $this->departementRepository->findDepartementEtudiant($etudiant);
            $this->session->getSession()->set('departement', $departement);
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        }

        if ($enseignant) {
            $departements = $this->departementRepository->findDepartementEnseignant($enseignant);

            if (0 === count($departements)) {
                return new RedirectResponse($this->urlGenerator->generate('app_login', ['message' => 'pas_departement']));
            }

            $departement = $this->departementRepository->findDepartementEnseignantDefaut($enseignant);
            dump($departement);
            if (0 === count($departement)) {
                dump($this->urlGenerator->generate('app_choix_departement', [], UrlGeneratorInterface::ABSOLUTE_URL));
                return new RedirectResponse($this->urlGenerator->generate('app_choix_departement', [], UrlGeneratorInterface::ABSOLUTE_URL));
            }

            if (1 === count($departement)) {
                $this->session->getSession()->set('departement', $departement);
                return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
            }

            return new RedirectResponse($this->urlGenerator->generate('app_login', ['message' => 'pas_departement']));
        }

        return new RedirectResponse($this->getTargetPath(
            $request->getSession(),
            $firewallName
        ) ?? $this->router->generate('app_accueil', ['message' => 'erreur_role']));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->getFlashBag()->add('danger', 'Le nom d\'utilisateur ou le mot de passe est incorrect.');


        return new RedirectResponse(
            $this->router->generate('app_login')
        );
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
