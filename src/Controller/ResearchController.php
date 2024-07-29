<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Repository\MateriauxRepository;
use App\Repository\CategoriesRepository;

class ResearchController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/api/recherche",
     *     summary="Recherche de produits",
     *     description="Effectue une recherche de produits en fonction de différents critères.",
     *     tags={"Recherche"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Terme de recherche général (nom, description, etc.)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="materiaux",
     *         in="query",
     *         description="Liste de matériaux séparés par des virgules (ex: bois,metal)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="prixMin",
     *         in="query",
     *         description="Prix minimum",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="prixMax",
     *         in="query",
     *         description="Prix maximum",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="categories",
     *         in="query",
     *         description="Liste de catégories séparées par des virgules (ex: tables,chaises)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="enStock",
     *         in="query",
     *         description="Filtrer par disponibilité (1 pour en stock, 0 pour hors stock)",
     *         @OA\Schema(type="integer", enum={0, 1})
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Critère de tri (prix-asc, prix-desc, etc.)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits correspondants aux critères de recherche",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Produits::class, groups={"product:read"}))
     *         )
     *     )
     * )
     */
    #[Route('/api/recherche', name: 'app_recherche_api', methods: ['GET'])]
    public function recherche(
        Request $request,
        ProduitsRepository $produitsRepository,
        MateriauxRepository $materiauxRepository,
        CategoriesRepository $categoriesRepository
    ): JsonResponse {
        $searchTerm = $request->query->get('search');
        $materiaux = $request->query->get('materiaux');
        $prixMin = $request->query->get('prixMin');
        $prixMax = $request->query->get('prixMax');
        $categories = $request->query->get('categories');
        $enStock = $request->query->get('enStock');
        $sort = $request->query->get('sort');

        if ($materiaux !== "") {
            $materiaux = $materiauxRepository->findBy(['nom' => explode(',', $materiaux)]);
            $materiaux = array_map(fn ($materiau) => $materiau->getId(), $materiaux);
        } else {
            $materiaux = [];
        }

        if ($categories !== "") {
            $categories = $categoriesRepository->findBy(['categoryName' => explode(',', $categories)]);
            $categories = array_map(fn ($category) => $category->getId(), $categories);
        } else {
            $categories = [];
        }

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
