<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProduitsRepository; // Ajoute cet import
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class CategorieController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Récupérer toutes les catégories",
     *     description="Retourne la liste de toutes les catégories avec l'image d'un produit représentatif.",
     *     tags={"Catégories"},
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Categories::class, groups={"category:read"}))
     *         )
     *     )
     * )
     */
    #[Route('/api/categories', name: 'app_product_category_api', methods: ['GET'])]
    public function getCategorys(
        CategoriesRepository $categoryRepository // Utilise CategoriesRepository ici
    ): JsonResponse {
        $categories = $categoryRepository->findAllWithProductImage(); // Appelle la nouvelle méthode

        return $this->json($categories, 200, [], ['groups' => 'category:read']);
    }
    /**
     * @OA\Get(
     *     path="/api/categories/{categoryId}",
     *     summary="Récupérer une catégorie par son ID",
     *     description="Retourne les détails d'une catégorie spécifique.",
     *     tags={"Catégories"},
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID de la catégorie",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(ref=@Model(type=Categories::class, groups={"category:read"}))
     *     ),
     *     @OA\Response(response=404, description="Catégorie non trouvée")
     * )
     */
    #[Route('/api/categories/{categoryId}', name: 'app_category_api', methods: ['GET'])] // Nouvelle route
    public function getCategory(int $categoryId, CategoriesRepository $categoryRepository): JsonResponse
    {
        $category = $categoryRepository->find($categoryId);
        return $this->json($category, 200, [], ['groups' => 'category:read']);
    }
    /**
     * @OA\Get(
     *     path="/api/categories/{categoryId}/produits",
     *     summary="Récupérer les produits d'une catégorie",
     *     description="Retourne la liste des produits appartenant à une catégorie donnée.",
     *     tags={"Catégories"},
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID de la catégorie",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Produits::class, groups={"product:read"}))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Catégorie non trouvée")
     * )
     */
    #[Route('/api/categories/{categoryId}/produits', name: 'app_category_products_api', methods: ['GET'])]
    public function getCategoryProducts(
        int $categoryId,
        ProduitsRepository $produitsRepository
    ): JsonResponse {
        $products = $produitsRepository->findBy(['category' => $categoryId]);

        return $this->json($products, 200, [], ['groups' => 'product:read']);
    }
}
