<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitController extends AbstractController
{
    private $produitRepository;
    private $serializer;

    public function __construct(ProduitsRepository $produitRepository, SerializerInterface $serializer)
    {
        $this->produitRepository = $produitRepository;
        $this->serializer = $serializer;
    }

    #[Route('/api/produits', name: 'app_produit_api', methods: ['GET'])]
    public function getProduits(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $produits = $this->produitRepository->findBySearchTerm($searchTerm);
        } else {
            $produits = $this->produitRepository->findAll();
        }

        if (empty($produits)) {
            return $this->json(['message' => 'Aucun produit trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->serializer->serialize($produits, 'json', ['groups' => 'product:read']);
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    #[Route('/api/produit/{productId}', name: 'app_single_produit_api', methods: ['GET'])]
    public function getProduct(int $productId): JsonResponse
    {
        $product = $this->produitRepository->find($productId);

        if (!$product) {
            return $this->json(['message' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->serializer->serialize($product, 'json', ['groups' => 'product:read']);
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
