<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Récupère les commandes d'un utilisateur, regroupées par année.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @return array Un tableau de commandes regroupées par année.
     */
    public function findGroupedOrdersByUser(int $userId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT o.orderId, o.orderDate, o.totalAmount, od.product_id AS productId, od.quantity, od.unitPrice, p.name AS productName
             FROM App\Entity\Order o
             JOIN o.orderDetails od
             JOIN App\Entity\Product p WITH od.productId = p.productId 
             WHERE o.userId = :userId
             ORDER BY o.orderDate DESC'
        )->setParameter('userId', $userId);

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

        return $groupedOrders;
    }
}
