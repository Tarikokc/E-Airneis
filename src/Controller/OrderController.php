<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\OrderDetail;
use App\Entity\Produits;
use App\Entity\ProductPhoto;
use App\Repository\OrderRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class OrderController extends AbstractController
{
    private $orderRepository;
    private $userRepository;

    public function __construct(OrderRepository $orderRepository, UserRepository  $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/order/history/{userId}",
     *     summary="Historique des commandes d'un utilisateur",
     *     description="Récupère l'historique des commandes pour un utilisateur donné.",
     *     tags={"Commandes"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Historique des commandes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="order_id", type="integer"),
     *                 @OA\Property(property="order_date", type="string", format="date"),
     *                 @OA\Property(property="total_amount", type="number", format="float")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
    #[Route('/api/order/history/{userId}', name: 'app_order_history', methods: ['GET'])]
    public function history(int $userId, OrderRepository $orderRepository, EntityManagerInterface $entityManager): JsonResponse
    {

        $user = $entityManager->getRepository(User::class)->find($userId);


        if (!$user) {
            return $this->json(
                ['error' => 'Utilisateur non trouvé'],
                Response::HTTP_NOT_FOUND
            );
        }

        $orders = $orderRepository->findByUser($userId);

        $orderData = [];
        foreach ($orders as $order) {
            $orderData[] = [
                'order_id' => $order->getOrderId(),
                'order_date' => $order->getOrderDate()?->format('Y-m-d'),
                'total_amount' => $order->getTotalAmount(),
            ];
        }

        return $this->json($orderData);
    }
    /**
     * @OA\Post(
     *     path="/api/order/{userId}/create",
     *     summary="Créer une nouvelle commande",
     *     description="Crée une nouvelle commande pour un utilisateur donné.",
     *     tags={"Commandes"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la commande",
     *         @OA\JsonContent(
     *             required={"orderDate", "totalAmount"},
     *             @OA\Property(property="orderDate", type="string", format="date-time", example="2023-12-04T15:30:00Z"),
     *             @OA\Property(property="totalAmount", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commande créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Commande créée avec succès"),
     *             @OA\Property(property="orderId", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Données invalides"),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    #[Route('/api/order/{userId}/create', name: 'app_order_create', methods: ['POST'])]
    public function create(int $userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON data'], Response::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['orderDate', 'totalAmount'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Missing field: $field"], Response::HTTP_BAD_REQUEST);
            }
        }

        $orderDate = new \DateTime($data['orderDate']);
        $totalAmount = (float) $data['totalAmount'];

        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $order = new Order();
        $order->setUser($user);
        $order->setOrderDate($orderDate);
        $order->setTotalAmount($totalAmount);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json(
            ['message' => 'Commande créée avec succès', 'orderId' => $order->getOrderId()],
            Response::HTTP_CREATED
        );
    }


    /**
     * @OA\Post(
     *     path="/api/orders/{orderId}/details",
     *     summary="Ajouter des détails à une commande",
     *     description="Ajoute des articles (produits) à une commande existante.",
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         description="ID de la commande",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Détails des articles à ajouter",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="product_id", type="integer"),
     *                 @OA\Property(property="quantity", type="integer"),
     *                 @OA\Property(property="unit_price", type="number", format="float")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Détails de la commande ajoutés avec succès"
     *     ),
     *     @OA\Response(response=400, description="Données invalides"),
     *     @OA\Response(response=404, description="Commande non trouvée")
     * )
     */
    #[Route('/api/orders/{orderId}/details', name: 'app_add_order_details', methods: ['POST'])]
    public function addOrderDetails(
        int $orderId,
        Request $request,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $order = $orderRepository->find($orderId);

        if (!$order) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data || !is_array($data)) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($data as $item) {
            if (
                !isset($item['product_id']) ||
                !isset($item['quantity']) ||
                !isset($item['unit_price'])
            ) {
                return $this->json(['error' => 'Données d\'article incomplètes'], Response::HTTP_BAD_REQUEST);
            }

            $product = $entityManager->getRepository(Produits::class)->find($item['product_id']);
            if (!$product) {
                return $this->json(['error' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $orderDetail = new OrderDetail();
            $orderDetail->setOrder($order);
            $orderDetail->setProduct($product);
            $orderDetail->setQuantity($item['quantity']);
            $orderDetail->setUnitPrice($item['unit_price']);

            $entityManager->persist($orderDetail);
        }

        $entityManager->flush();

        return $this->json(['message' => 'Détails de la commande ajoutés avec succès'], Response::HTTP_CREATED);
    }


    // #[Route('/api/orders/{orderId}', name: 'app_order_get', methods: ['GET'])]
    // public function getOrder(int $orderId, OrderDetailRepository $orderDetailRepository): JsonResponse
    // {
    //     $orderDetails = $orderDetailRepository->findByOrderId($orderId);

    //     if (empty($orderDetails)) {
    //         return $this->json(['error' => 'Aucun détail trouvé pour cette commande'], Response::HTTP_NOT_FOUND);
    //     }

    //     $formattedDetails = [];
    //     foreach ($orderDetails as $detail) {
    //         $product = $detail->getProduct();
    //         $primaryPhoto = $product->getProductPhotos()->filter(function ($photo) {
    //             return $photo->isIsPrimary();
    //         })->first();

    //         $formattedDetails[] = [
    //             'product_id' => $product->getProductId(),
    //             'product_name' => $product->getNom(),
    //             'product_description' => $product->getDescription(),
    //             'product_image' => $primaryPhoto ? $primaryPhoto->getPhotoUrl() : null,
    //             'quantity' => $detail->getQuantity(),
    //             'unit_price' => $detail->getUnitPrice(),
    //         ];
    //     }

    //     return $this->json($formattedDetails, Response::HTTP_OK);
    // }
    #[Route('/api/orders/{orderId}', name: 'app_order_get', methods: ['GET'])]
    public function getOrder(int $orderId, OrderRepository $orderRepository, SerializerInterface $serializer): JsonResponse
    {
        $order = $orderRepository->find($orderId);

        if (!$order) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Sérialisation avec le groupe 'order_details_with_products'
        $jsonContent = $serializer->serialize($order, 'json', ['groups' => 'order_details_with_products']);

        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
}
