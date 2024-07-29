<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produits;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;


class PanierController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/panier/add/{id}/{userId}",
     *     summary="Ajouter un produit au panier",
     *     description="Ajoute un produit au panier de l'utilisateur spécifié. Si le produit existe déjà dans le panier, la quantité est incrémentée.",
     *     tags={"Panier"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit à ajouter",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit ajouté au panier avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Panier") 
     *     ),
     *     @OA\Response(response=404, description="Produit ou utilisateur non trouvé")
     * )
     */
    #[Route('/panier/add/{id}/{userId}', name: 'ajouter_au_panier', methods: ['POST'])]
    public function ajouterAuPanier(Request $request, Produits $produit, int $userId, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse  // <-- Changez le type de retour et ajoutez SerializerInterface
    {
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $panier = $entityManager->getRepository(Panier::class)->findOneBy([
            'produit' => $produit,
            'user' => $user,
        ]);

        if ($panier) {
            $panier->setQuantite($panier->getQuantite() + 1);
        } else {
            $panier = new Panier();
            $panier->setProduit($produit);
            $panier->setUser($user);
            $panier->setQuantite(1);
            $panier->setPrix($produit->getPrix());
        }

        $entityManager->persist($panier);
        $entityManager->flush();

        $jsonContent = $serializer->serialize($panier, 'json', ['groups' => 'panier:read']);
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    /**
     * @OA\Post(
     *     path="/api/panier/transfert",
     *     summary="Transférer le panier invité vers un utilisateur",
     *     description="Transfère les articles du panier d'un invité (identifié par un cookie) vers le panier d'un utilisateur authentifié.",
     *     tags={"Panier"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du panier invité et ID de l'utilisateur (optionnel)",
     *         @OA\JsonContent(
     *             required={"guestCart"},
     *             @OA\Property(property="guestCart", type="array", @OA\Items(ref="#/components/schemas/PanierItem")),
     *             @OA\Property(property="userId", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Panier transféré avec succès"
     *     ),
     *     @OA\Response(response=400, description="Données de panier invalides ou panier invité vide")
     *     @OA\Response(response=404, description="Utilisateur non trouvé (si userId est fourni)")
     * )
     */
    #[Route('/api/panier/transfert', name: 'api_transfert_panier', methods: ['POST'])]
    public function transfererPanier(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = $request->request->get('userId');
        $guestCart = json_decode($request->request->get('guestCart'), true);

        if ($guestCart === null || empty($guestCart)) {
            return $this->json(['error' => 'Le panier invité est vide.'], Response::HTTP_BAD_REQUEST);
        }

        if ($userId === null || $userId === '') {
            $user = new User();
            $user->setEmail('guest@example.com');
            $entityManager->persist($user);
        } else {
            $user = $entityManager->getRepository(User::class)->find($userId);
            if (!$user) {
                return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
            }
        }

        foreach ($guestCart as $item) {
            if (!isset($item['productId']) || !is_numeric($item['productId'])) {
                continue;
            }

            $produit = $entityManager->getRepository(Produits::class)->find($item['productId']);
            if (!$produit) {
                continue;
            }

            $panier = $entityManager->getRepository(Panier::class)->findOneBy([
                'produit' => $produit,
                'user' => $user,
            ]);

            if ($panier) {
                $panier->setQuantite($panier->getQuantite() + $item['quantite']);
            } else {
                $panier = new Panier();
                $panier->setProduit($produit);
                $panier->setUser($user);
                $panier->setQuantite($item['quantite']);
                $panier->setPrix($produit->getPrix());
                $entityManager->persist($panier);
            }
        }

        $entityManager->flush();

        return $this->json(['success' => 'Panier transféré avec succès', 'userId' => $user->getId()], Response::HTTP_OK);
    }


    /**
     * @OA\Get(
     *     path="/api/panier/{userId}",
     *     summary="Récupérer le panier d'un utilisateur",
     *     description="Récupère les articles du panier de l'utilisateur spécifié, avec les détails des produits.",
     *     tags={"Panier"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Panier de l'utilisateur",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PanierItem"))
     *     ),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    #[Route('/api/panier/{userId}', name: 'api_get_panier', methods: ['GET'])]
    public function getPanier(int $userId, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $panierItems = $entityManager->getRepository(Panier::class)->findPanierWithProductDetails($userId);

        $jsonContent = $serializer->serialize($panierItems, 'json', [
            'groups' => ['panier:read', 'produit:read', 'categories:read', 'materiaux:read'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }



    /**
     * @OA\Put(
     *     path="/api/panier/{productId}/{userId}",
     *     summary="Mettre à jour un article du panier",
     *     description="Modifie la quantité d'un article existant dans le panier d'un utilisateur.",
     *     tags={"Panier"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="L'ID du produit à mettre à jour",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="L'ID de l'utilisateur propriétaire du panier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelle quantité de l'article",
     *         @OA\JsonContent(
     *             required={"quantite"},
     *             @OA\Property(property="quantite", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantité mise à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Panier")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Quantité invalide ou autre erreur"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé dans le panier"
     *     )
     * )
     */
    #[Route('/api/panier/{productId}/{userId}', name: 'api_update_panier', methods: ['PUT'])]
    public function updatePanier(Request $request, int $productId, int $userId, EntityManagerInterface $entityManager): JsonResponse
    {

        $panier = $entityManager->getRepository(Panier::class)->findOneBy([
            'produit' => $productId,
            'user' => $userId,
        ]);


        if (!$panier) {
            return $this->json(['error' => 'Article du panier non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);


        $newQuantity = $data['quantite'] ?? null;

        if ($newQuantity === null || $newQuantity < 1) {
            return $this->json(['error' => 'Quantité invalide'], Response::HTTP_BAD_REQUEST);
        }

        $panier->setQuantite($newQuantity);
        $entityManager->flush();

        return $this->json(['success' => 'Quantité mise à jour', 'panier' => $panier], 200, [], [
            'groups' => 'panier',
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/panier/{productId}/{userId}",
     *     summary="Supprimer un article du panier",
     *     description="Retire un article du panier d'un utilisateur.",
     *     tags={"Panier"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="L'ID du produit à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="L'ID de l'utilisateur propriétaire du panier",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé dans le panier"
     *     )
     * )
     */
    #[Route('/api/panier/{productId}/{userId}', name: 'api_delete_panier', methods: ['DELETE'])]
    public function deletePanier(int $productId, int $userId, EntityManagerInterface $entityManager): JsonResponse
    {

        $panier = $entityManager->getRepository(Panier::class)->findOneBy([
            'produit' => $productId,
            'user' => $userId,
        ]);

        if (!$panier) {
            return $this->json(['error' => 'Article du panier non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($panier);
        $entityManager->flush();

        return $this->json(['success' => 'Article supprimé du panier']);
    }
}
