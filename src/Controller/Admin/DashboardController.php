<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Entity\Portfolio;
use App\Entity\Trace;
use App\Entity\Users;
use App\Entity\Validation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('Admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('UniFolio')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToUrl('Site public', 'fas fa-book', '/');
//        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-exit');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Gestion des utilisateurs', 'fas fa-list', Users::class);
//        yield MenuItem::linkToRoute('Création de compte', 'fas fa-user-plus', 'app_users_new');

        yield MenuItem::section('Portfolios');
        yield MenuItem::linkToCrud('Gestion des traces', 'fas fa-list', Trace::class);
        yield MenuItem::linkToCrud('Gestion des portfolios', 'fas fa-list', Portfolio::class);
        yield MenuItem::linkToCrud('Gestion des commentaires', 'fas fa-list', Commentaire::class);
        yield MenuItem::linkToCrud('Gestion des validations', 'fas fa-list', Validation::class);
    }

//    public function configureUserMenu(UserInterface $user): UserMenu
//    {
//        return parent::configureUserMenu($user)
//
//            ->displayUserName(false);
//    }

}
