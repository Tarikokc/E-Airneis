<?php

namespace App\Controller;

use App\Repository\MateriauxRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MateriauController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @OA\Get(
     *     path="/api/materiaux",
     *     summary="Récupérer tous les matériaux",
     *     description="Retourne la liste de tous les matériaux disponibles.",
     *     tags={"Matériaux"},
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Materiaux::class, groups={"materiaux:read"}))
     *         )
     *     )
     * )
     */
    #[Route('/api/materiaux', name: 'app_materiaux_api', methods: ['GET'])]
    public function getMateriaux(MateriauxRepository $materiauxRepository): JsonResponse
    {
        $materiaux = $materiauxRepository->findAll();
        $jsonContent = $this->serializer->serialize($materiaux, 'json', ['groups' => 'materiaux:read']);
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * @OA\Get(
     *     path="/api/produits/{productId}/materiaux",
     *     summary="Récupérer les matériaux d'un produit",
     *     description="Retourne la liste des matériaux utilisés pour fabriquer un produit donné.",
     *     tags={"Matériaux"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Materiaux::class, groups={"materiaux:read"}))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    #[Route('/api/produits/{productId}/materiaux', name: 'app_produit_materiaux_api', methods: ['GET'])]
    public function getProduitMateriaux(
        int $productId,
        ProduitsRepository $produitsRepository,
        MateriauxRepository $materiauxRepository
    ): JsonResponse {
        $produit = $produitsRepository->find($productId);

        if (!$produit) {
            return $this->json(['message' => 'Produit non trouvé'], 404);
        }

        $materiaux = $materiauxRepository->findByProduits($produit);
        $jsonContent = $this->serializer->serialize($materiaux, 'json', ['groups' => 'materiaux:read']);
        return new JsonResponse($jsonContent, 200, [], true);
    }
}
