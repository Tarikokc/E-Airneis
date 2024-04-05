<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MateriauController extends AbstractController
{
    #[Route('/materiau', name: 'app_materiau')]
    public function index(): Response
    {
        return $this->render('materiau/index.html.twig', [
            'controller_name' => 'MateriauController',
        ]);
    }
}
