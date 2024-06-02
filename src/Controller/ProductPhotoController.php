<?php
// src/Controller/ProductPhotoController.php

namespace App\Controller;

use App\Entity\Produits; // Importez l'entité Produits
use App\Repository\ProductPhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductPhotoController extends AbstractController
{
    #[Route('/api/products/{id}/photos', name: 'app_product_photos', methods: ['GET'])]
    public function getProductPhotos(Produits $product, ProductPhotoRepository $productPhotoRepository): JsonResponse
    {
        $photos = $productPhotoRepository->findBy(['product' => $product]); // Utilisez l'entité Produits

        return $this->json($photos, 200, [], ['groups' => 'product:read']); // Renvoyer les photos en JSON avec le bon groupe
    }
}
