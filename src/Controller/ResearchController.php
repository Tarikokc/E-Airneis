<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// ... (vos autres imports)
use App\Repository\MateriauxRepository; // Pour récupérer les IDs des matériaux
use App\Repository\CategoriesRepository; // Pour récupérer les IDs des catégories

class ResearchController extends AbstractController
{
    #[Route('/api/recherche', name: 'app_recherche_api', methods: ['GET'])]
    public function recherche(
        Request $request,
        ProduitsRepository $produitsRepository,
        MateriauxRepository $materiauxRepository,
        CategoriesRepository $categoriesRepository
    ): JsonResponse {
        // Récupérer les paramètres de recherche
        $searchTerm = $request->query->get('search');
        $materiaux = $request->query->get('materiaux');
        $prixMin = $request->query->get('prixMin');
        $prixMax = $request->query->get('prixMax');
        $categories = $request->query->get('categories');
        $enStock = $request->query->get('enStock');
        $sort = $request->query->get('sort'); // Ex: 'prix-asc', 'prix-desc', etc.

        // Traiter les paramètres materiaux et categories (CORRIGÉ)
        if ($materiaux !== "") { // Vérifier si la chaîne est vide
            $materiaux = $materiauxRepository->findBy(['nom' => explode(',', $materiaux)]);
            $materiaux = array_map(fn ($materiau) => $materiau->getId(), $materiaux);
        } else {
            $materiaux = []; // Passer un tableau vide si la chaîne est vide
        }

        if ($categories !== "") { // Vérifier si la chaîne est vide
            $categories = $categoriesRepository->findBy(['categoryName' => explode(',', $categories)]);
            $categories = array_map(fn ($category) => $category->getId(), $categories);
        } else {
            $categories = []; // Passer un tableau vide si la chaîne est vide
        }

        // Appeler le repository pour effectuer la recherche (avec les tableaux $materiaux et $categories)
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
