<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends AbstractController
{
    #[Route('/api/recherche', name: 'app_recherche_api', methods: ['GET'])]
    public function recherche(Request $request, ProduitsRepository $produitsRepository): JsonResponse
    {
        // Récupérer les paramètres de recherche
        $searchTerm = $request->query->get('search');
        $materiaux = $request->query->get('materiaux');
        $prixMin = $request->query->get('prixMin');
        $prixMax = $request->query->get('prixMax');
        $categories = $request->query->get('categories');
        $enStock = $request->query->get('enStock');
        $sort = $request->query->get('sort'); // Ex: 'prix-asc', 'prix-desc', etc.

        // Appeler le repository pour effectuer la recherche
        $products = $produitsRepository->findBySearchCriteria(
            $searchTerm,
            $materiaux,
            $prixMin,
            $prixMax,
            $categories,
            $enStock,
            $sort
        );

        return $this->json($products, 200, [], ['groups' => 'product:read']);
    }
}
