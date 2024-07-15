<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Entity\ProductPhoto;
use App\Entity\Categories;
use App\Entity\User;


class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();
        return $this->render('@EasyAdmin/page/content.html.twig');


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
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('E Airneis');
    }

    public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
    // yield MenuItem::linkToCrud('Paniers', 'fa fa-shopping-basket', Panier::class);
    // yield MenuItem::linkToCrud('Détails commandes', 'fa fa-list-alt', OrderDetail::class);
    // yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);
    yield MenuItem::linkToCrud('Catégories', 'fa fa-tags', Categories::class);
    yield MenuItem::linkToCrud('Photos produits', 'fa fa-image', ProductPhoto::class);
    yield MenuItem::linkToCrud('Produits', 'fa fa-box-open', Produits::class);
    yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
}

}
