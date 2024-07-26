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

class OrderController extends AbstractController
{
    private $orderRepository;
    private $userRepository;

    public function __construct(OrderRepository $orderRepository, UserRepository  $userRepository ) {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;


    }
    #[Route('/api/order/history/{userId}', name: 'app_order_history', methods: ['GET'])]
    public function history(int $userId , OrderRepository $orderRepository, EntityManagerInterface $entityManager): JsonResponse
    {
       
        // $user = new User();
        // $Test = $this -> userRepository -> findOneBy(["email" => "dadada@ia.com"]);
        $user = $entityManager->getRepository(User::class)->find($userId);


        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 
            Response::HTTP_NOT_FOUND);
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
}


