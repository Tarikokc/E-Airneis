<?php

// namespace App\Controller;

// use App\Entity\Panier;
// use App\Entity\Produits;
// use App\Entity\User;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class PanierController extends AbstractController
// {
//     #[Route('/panier/add/{id}/{userId}', name: 'ajouter_au_panier', methods: ['POST'])]
//     public function ajouterAuPanier(Request $request, Produits $produit, int $userId, EntityManagerInterface $entityManager): Response
//     {
//         // 1. Récupérer l'utilisateur avec l'ID $userId (identique à votre route /api/account/{userId})
//         $user = $entityManager->getRepository(User::class)->find($userId);

//         if (!$user) {
//             return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
//         }

//         // 2. Vérifier si un élément du panier existe déjà pour ce produit et cet utilisateur
//         $panier = $entityManager->getRepository(Panier::class)->findOneBy([
//             'produit' => $produit, 
//             'user' => $user,           
//         ]);

//         if ($panier) {
//             // Si l'élément existe, mettre à jour la quantité
//             $panier->setQuantite($panier->getQuantite() + 1); // Ou ajustez selon la quantité désirée
//         } else {
//             // Sinon, crée un nouvel élément de panier
//             $panier = new Panier();
//             $panier->setProduit($produit);
//             $panier->setUser($user);
//             $panier->setQuantite(1); 
//             $panier->setPrix($produit->getPrix()); // Récupérer le prix du produit et le définir

//         }

//         // 4. Persister l'élément du panier
//         $entityManager->persist($panier);
//         $entityManager->flush();

//         // 5. Rediriger ou renvoyer une réponse JSON (selon votre besoin)
//         return $this->json($panier);

//     }
// }
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
use Symfony\Component\Serializer\SerializerInterface; // <-- Ajoutez cet import


class PanierController extends AbstractController
{
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

    #[Route('/api/panier/{productId}/{userId}', name: 'api_update_panier', methods: ['PUT'])]
    public function updatePanier(Request $request, int $productId, int $userId, EntityManagerInterface $entityManager): JsonResponse
    {

        $panier = $entityManager->getRepository(Panier::class)->findOneBy([
            'produit' => $productId, // Utilisez 'product' (qui fait référence à l'entité Produit)
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
