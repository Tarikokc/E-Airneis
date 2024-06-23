<?php

namespace App\Controller;

use App\Repository\MateriauxRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface; // Ajoutez cette ligne

class MateriauController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer) // Injectez le sérialiseur
    {
        $this->serializer = $serializer;
    }

    #[Route('/api/materiaux', name: 'app_materiaux_api', methods: ['GET'])]
    public function getMateriaux(MateriauxRepository $materiauxRepository): JsonResponse
    {
        $materiaux = $materiauxRepository->findAll();
        $jsonContent = $this->serializer->serialize($materiaux, 'json', ['groups' => 'materiaux:read']);
        return new JsonResponse($jsonContent, 200, [], true);
    }

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
