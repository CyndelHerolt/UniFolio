<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\AnneeUniversitaire;
use App\Entity\ApcApprentissageCritique;
use App\Entity\ApcNiveau;
use App\Entity\ApcParcours;
use App\Entity\ApcReferentiel;
use App\Entity\Commentaire;
use App\Entity\Competence;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Page;
use App\Entity\Portfolio;
use App\Entity\Semestre;
use App\Entity\Templates;
use App\Entity\Trace;
use App\Entity\TypeGroupe;
use App\Entity\Users;
use App\Entity\Validation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $site = $_ENV['SITE'];
        return $this->render('Admin/dashboard.html.twig', [
            'site' => $site,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('UniFolio')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        // récupérer la variable SITE de .env
        if ($_ENV['SITE'] === 'IUTTroyes') {
            yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
            yield MenuItem::linkToUrl('Site étudiant', 'fas fa-user', '/dashboard?_switch_user=etudiant');
            yield MenuItem::linkToUrl('Site enseignant', 'fas fa-user', '/dashboard?_switch_user=enseignant');
            yield MenuItem::linkToLogout('Déconnexion', 'fa fa-arrow-right-from-bracket');
        }
        yield MenuItem::section('Structure');
        yield MenuItem::linkToCrud('Gestion des départements', 'fas fa-list', Departement::class);
        yield MenuItem::linkToCrud('Gestion des diplomes', 'fas fa-list', Diplome::class);
        yield MenuItem::linkToCrud('Gestion des années', 'fas fa-list', Annee::class);
        yield MenuItem::linkToCrud('Gestion des semestres', 'fas fa-list', Semestre::class);
        yield MenuItem::linkToCrud('Gestion des types de groupes', 'fas fa-list', TypeGroupe::class);
        yield MenuItem::linkToCrud('Gestion des groupes', 'fas fa-list', Groupe::class);

        yield MenuItem::section('Apc');
        yield MenuItem::linkToCrud('Gestion des référentiels', 'fas fa-list', ApcReferentiel::class);
        yield MenuItem::linkToCrud('Gestion des parcours', 'fas fa-list', ApcParcours::class);
        yield MenuItem::linkToCrud('Gestion des compétences', 'fas fa-list', Competence::class);
        yield MenuItem::linkToCrud('Gestion des niveaux', 'fas fa-list', ApcNiveau::class);
        yield MenuItem::linkToCrud('Gestion des apprentissages critiques', 'fas fa-list', ApcApprentissageCritique::class);


        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Gestion des etudiants', 'fas fa-list', Etudiant::class);
        yield MenuItem::linkToCrud('Gestion des enseignants', 'fas fa-list', Enseignant::class);

        yield MenuItem::section('Portfolios');
        yield MenuItem::linkToCrud('Gestion des traces', 'fas fa-list', Trace::class);
        yield MenuItem::linkToCrud('Gestion des pages', 'fas fa-list', Page::class);
        yield MenuItem::linkToCrud('Gestion des portfolios', 'fas fa-list', Portfolio::class);
        yield MenuItem::linkToCrud('Gestion des commentaires', 'fas fa-list', Commentaire::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
//                MenuItem::LinkToRoute('Mon profil', 'fas fa-user', 'app_profil'),
            ]);
    }

    // défini des paramètres pour l'ensemble des CRUD controller
    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setPaginatorPageSize(30)
            ;
    }
}
