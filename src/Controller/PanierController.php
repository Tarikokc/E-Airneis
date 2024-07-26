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
        // 1. Récupérer l'utilisateur avec l'ID $userId
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // 2. Vérifier si un élément du panier existe déjà pour ce produit et cet utilisateur
        $panier = $entityManager->getRepository(Panier::class)->findOneBy([
            'produit' => $produit,
            'user' => $user,
        ]);

        if ($panier) {
            // Si l'élément existe, mettre à jour la quantité
            $panier->setQuantite($panier->getQuantite() + 1);
        } else {
            // Sinon, crée un nouvel élément de panier
            $panier = new Panier();
            $panier->setProduit($produit);
            $panier->setUser($user);
            $panier->setQuantite(1);
            $panier->setPrix($produit->getPrix());
        }

        // 4. Persister l'élément du panier
        $entityManager->persist($panier);
        $entityManager->flush();

        // 5. Serialiser l'objet Panier en JSON et le renvoyer
        $jsonContent = $serializer->serialize($panier, 'json', ['groups' => 'panier:read']); // Personnalisez les groupes de sérialisation si nécessaire
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true); // Le dernier argument 'true' permet de déserialiser les objets
    }

    #[Route('/api/panier/{userId}', name: 'api_get_panier', methods: ['GET'])]
    public function getPanier(int $userId, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $panierItems = $entityManager->getRepository(Panier::class)->findPanierWithProductDetails($userId); 

        // Configuration de la sérialisation pour inclure l'ID du panier
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

        // $panier = $entityManager->getRepository(Panier::class)->find($panierId);


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
        
        // $panier = $entityManager->getRepository(Panier::class)->find($panierId);

        if (!$panier) {
            return $this->json(['error' => 'Article du panier non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($panier);
        $entityManager->flush();

        return $this->json(['success' => 'Article supprimé du panier']);
    }
}
