<?php

namespace App\Controller;

use App\Repository\OrderRepository;  // Importez votre repository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/api/commandes', name: 'api_commandes_get_all', methods: ['GET'])]
    public function getAllOrders(OrderRepository $orderRepository): JsonResponse
{
    $orders = $orderRepository->findAllWithDetails(); // Récupérez les commandes avec leurs détail

        // Transformation des commandes en tableau associatif
        $ordersArray = [];
        foreach ($orders as $order) {
            $orderDetailsArray = [];
            foreach ($order->getOrderDetails() as $orderDetail) {
                $orderDetailsArray[] = [
                    'productId' => $orderDetail->getProduct()->getId(),
                    'productName' => $orderDetail->getProduct()->getName(),
                    'quantity' => $orderDetail->getQuantity(),
                    'unitPrice' => $orderDetail->getUnitPrice(),
                ];
            }
            $ordersArray[] = [
                'orderId' => $order->getId(),
                'userId' => $order->getUserId(),
                'orderDate' => $order->getOrderDate()->format('Y-m-d'), // Formatez la date
                'totalAmount' => $order->getTotalAmount(),
                'products' => $orderDetailsArray,
            ];
        }

        return $this->json($ordersArray); // Retourne une réponse JSON
    }
}
