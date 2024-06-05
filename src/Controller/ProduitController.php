<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class ProduitController extends AbstractController
// {
//     #[Route('/produit', name: 'app_produit')]
//     public function index(): Response
//     {
//         return $this->render('produit/index.html.twig', [
//             'controller_name' => 'ProduitController',
//         ]);
//     }
// }


// src/Controller/ProduitController.php
namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response; // Correction ici
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProduitController extends AbstractController
{
    private $normalizer;
    private $produitRepository;

    public function __construct(NormalizerInterface $normalizer, ProduitsRepository $produitRepository)
    {
        $this->normalizer = $normalizer;
        $this->produitRepository = $produitRepository;
    }

    #[Route('/api/produits', name: 'app_produit_api', methods: ['GET'])]
    public function getProduits(): JsonResponse
    {
        $produits = $this->produitRepository->findAll();

        if (!$produits) {
            return $this->json([
                'message' => 'Aucun produit trouvé.'
            ], Response::HTTP_NOT_FOUND);
        }

        $produitsNormalises = [];
        foreach ($produits as $produit) {
            $produitsNormalises[] = $this->normalizer->normalize($produit, null, ['groups' => 'products']);
        }

        // return $this->json($produitsNormalises);
        // return $this->json($produits); 
        return $this->json($produits, 200, [], ['groups' => 'product:read']);


    }

    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        // Récupérer les produits depuis l'API
        // $response = $this->forward('App\Controller\ProduitController::getProduits'); // Appelle la méthode de l'API
        // $produits = json_decode($response->getContent(), true); // Décode la réponse JSON
        $produits = $this->produitRepository->findAllWithPhotos();

        return $this->render('produit/index.html.twig', [
            // 'controller_name' => 'ProduitController',
            'produits' => $produits, // Passe les produits décodés au template

        ]);
    }
}
