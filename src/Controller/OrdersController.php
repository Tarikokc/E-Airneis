<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry; // Pour l'accès à la base de données

class OrdersController extends AbstractController
{
    #[Route('/orders', name: 'app_orders')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        // Récupération de l'ID de l'utilisateur connecté 
        $userId = $this->getUser()->getId();

        // Requête DQL adaptée à votre base de données
        $query = $entityManager->createQuery(
            'SELECT o.order_id AS orderId, o.order_date AS orderDate, o.total_amount AS totalAmount, 
                    od.product_id AS productId, od.quantity, od.unit_price AS unitPrice, p.name AS productName
             FROM App\Entity\Order o
             JOIN o.orderDetails od
             JOIN App\Entity\Product p WITH od.product_id = p.product_id 
             WHERE o.user_id = :userId
             ORDER BY o.order_date DESC'
        )->setParameter('userId', $userId); // Utilisation de user_id pour la correspondance avec la table users


        $ordersData = $query->getResult();

        // Regroupement des commandes par année 
        $groupedOrders = [];
        foreach ($ordersData as $orderData) {
            $year = $orderData['orderDate']->format('Y');

            // Créez un tableau d'objets 'product' pour chaque commande
            if (!isset($groupedOrders[$year])) {
                $groupedOrders[$year] = [];
            }

            // Vérifiez si la commande existe déjà dans le groupement de l'année
            $orderIndex = array_search($orderData['orderId'], array_column($groupedOrders[$year], 'orderId'));

            if ($orderIndex === false) {
                // La commande n'existe pas encore, créez un nouvel objet de commande
                $groupedOrders[$year][] = [
                    'orderId' => $orderData['orderId'],
                    'orderDate' => $orderData['orderDate'],
                    'totalAmount' => $orderData['totalAmount'],
                    'products' => [
                        [
                            'productId' => $orderData['productId'],
                            'productName' => $orderData['productName'], // Inclure le nom du produit
                            'quantity' => $orderData['quantity'],
                            'unitPrice' => $orderData['unitPrice']
                        ]
                    ]
                ];
            } else {
                // La commande existe déjà, ajoutez le produit à la liste des produits de la commande
                $groupedOrders[$year][$orderIndex]['products'][] = [
                    'productId' => $orderData['productId'],
                    'productName' => $orderData['productName'], // Inclure le nom du produit
                    'quantity' => $orderData['quantity'],
                    'unitPrice' => $orderData['unitPrice']
                ];
            }
        }

        // Rendu de la vue Twig avec les données
        return $this->render('orders/index.html.twig', [
            'groupedOrders' => $groupedOrders,
        ]);
    }
}
