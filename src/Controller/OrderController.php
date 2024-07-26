<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    private $orderRepository;
    private $userRepository;

    public function __construct(OrderRepository $orderRepository, UserRepository  $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }
    #[Route('/api/order/history/{userId}', name: 'app_order_history', methods: ['GET'])]
    public function history(int $userId, OrderRepository $orderRepository, EntityManagerInterface $entityManager): JsonResponse
    {

        // $user = new User();
        // $Test = $this -> userRepository -> findOneBy(["email" => "dadada@ia.com"]);
        $user = $entityManager->getRepository(User::class)->find($userId);


        if (!$user) {
            return $this->json(
                ['error' => 'Utilisateur non trouvé'],
                Response::HTTP_NOT_FOUND
            );
        }

        $orders = $orderRepository->findByUser($userId); // Utilisation du repository

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

    // #[Route('/api/order/{userId}/create', name: 'app_order_create', methods: ['POST'])]
    // public function create(int $userId, Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     // 1. Récupérer les données de la requête (sans orderId)
    //     $orderDate = new \DateTime($request->request->get('orderDate'));
    //     $totalAmount = $request->request->get('totalAmount');


    //     // Récupérer l'utilisateur en utilisant l'ID de la route
    //     $user = $entityManager->getRepository(User::class)->find($userId);
    //     if (!$user) {
    //         return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
    //     }

    //     // 4. Créer l'entité Order (sans setOrderId)
    //     $order = new Order();
    //     $order->setUser($user);
    //     $order->setOrderDate($orderDate);
    //     $order->setTotalAmount($totalAmount);

    //     // 5. Sauvegarder en base de données
    //     $entityManager->persist($order);
    //     $entityManager->flush();

    //     // 6. Renvoyer une réponse (vous pouvez renvoyer l'orderId généré)
    //     return $this->json(['message' => 'Commande créée avec succès', 'orderId' => $order->getOrderId()]);
    // }
    #[Route('/api/order/{userId}/create', name: 'app_order_create', methods: ['POST'])]
    public function create(int $userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1. Get Data from Request (JSON body)
        $data = json_decode($request->getContent(), true); 
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON data'], Response::HTTP_BAD_REQUEST);
        }
        
        // Check for required fields
        $requiredFields = ['orderDate', 'totalAmount'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Missing field: $field"], Response::HTTP_BAD_REQUEST);
            }
        }

        // 2. Validate Data 
        $orderDate = new \DateTime($data['orderDate']); // Handle invalid date format
        $totalAmount = (float) $data['totalAmount']; // Ensure numeric value

        // 3. Fetch User
        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // 4. Create and Populate Order Entity
        $order = new Order();
        $order->setUser($user);
        $order->setOrderDate($orderDate);
        $order->setTotalAmount($totalAmount); // Type-casting to float

        // 5. Persist and Flush
        $entityManager->persist($order);
        $entityManager->flush();

        // 6. Return Success Response
        return $this->json(
            ['message' => 'Commande créée avec succès', 'orderId' => $order->getOrderId()], 
            Response::HTTP_CREATED
        );
    }
}
