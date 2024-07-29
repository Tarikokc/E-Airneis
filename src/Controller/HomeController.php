<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 

use Symfony\Component\Routing\Annotation\Route; 

use Symfony\Component\Routing\RouterInterface;

class HomeController extends AbstractController 

{
    #[Route('/', name: 'app_home')]
    public function index(RouterInterface $router): Response
    {
        $routes = $router->getRouteCollection();
        $apiRoutes = [];

        foreach ($routes as $route) {
            if (str_starts_with($route->getPath(), '/api')) {
                $apiRoutes[] = $route;
            }
        }

        return $this->render('home/index.html.twig', [
            'apiRoutes' => $apiRoutes,
        ]);
    }
}