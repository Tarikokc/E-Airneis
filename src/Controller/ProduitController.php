<?php

// src/Controller/ProduitController.php
namespace App\Controller;

use App\Repository\ProduitsRepository;
use App\Entity\Produits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
class ProduitController extends AbstractController
{
    private $produitRepository;
    private $serializer;

    public function __construct(ProduitsRepository $produitRepository, SerializerInterface $serializer)
    {
        $this->produitRepository = $produitRepository;
        $this->serializer = $serializer;
    }
    /**
     * @OA\Get(
     *     path="/api/produits",
     *     summary="Récupère la liste de tous les produits",
     *     description="Permet de récupérer la liste de tous les produits.",
     *     tags={"Produits"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Terme de recherche pour filtrer les produits",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Produits::class, groups={"product:read", "category:read", "materiaux:read"}))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Aucun produit trouvé")
     * )
     */

    #[Route('/api/produits', name: 'app_produit_api', methods: ['GET'])]
    public function getProduits(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $searchTerm = $request->query->get('search');
        $produits = $this->produitRepository->findAll();

        if ($searchTerm) {
            $produits = $this->produitRepository->findBySearchTerm($searchTerm);
        } else {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder
                ->select('p', 'm')
                ->from(Produits::class, 'p')
                ->leftJoin('p.materiaux', 'm');

            $produits = $queryBuilder->getQuery()->getResult();
        }

        if (empty($produits)) {
            return $this->json(['message' => 'Aucun produit trouvé.'], Response::HTTP_NOT_FOUND);
        }
        foreach ($produits as $produit) {
            $produit->getMateriaux()->toArray();
        }
        $jsonContent = $this->serializer->serialize($produits, 'json', ['groups' => ['product:read', 'category:read', 'materiaux:read']]);
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    /**
     * @OA\Get(
     *     path="/api/produit/{productId}",
     *     summary="Récupère un produit par son ID",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/Produits")
     *     ),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    #[Route('/api/produit/{productId}', name: 'app_single_produit_api', methods: ['GET'])]
    public function getProduct(int $productId): JsonResponse
    {
        $product = $this->produitRepository->findById($productId);

        if (!$product) {
            return $this->json(['message' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        $product->getCategory()->getCategoryId();


        $jsonContent = $this->serializer->serialize($product, 'json', ['groups' => ['product:read', 'category:read', 'materiaux:read']]);

        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        $produits = $this->produitRepository->findAllWithPhotos();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}
