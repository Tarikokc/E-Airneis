<?php

// src/Controller/ProductCategoryController.php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository; // Ajoute cet import
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/api/categories', name: 'app_product_category_api', methods: ['GET'])]
    public function getCategories(
        CategoriesRepository $categoryRepository // Utilise CategoriesRepository ici
    ): JsonResponse {
        $categories = $categoryRepository->findAllWithProductImage(); // Appelle la nouvelle méthode

        return $this->json($categories, 200, [], ['groups' => 'category:read']);
    }
}
