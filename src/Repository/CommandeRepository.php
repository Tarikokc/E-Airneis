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
     * @return Order[] Returns an array of Order objects with their details
     */
    public function findAllWithDetails(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.orderDetails', 'od') // Jointure avec les détails de commande
            ->leftJoin('od.product', 'p')   // Jointure avec les produits
            ->addSelect('od')                // Sélectionner les détails de commande
            ->addSelect('p')                 // Sélectionner les produits
            ->getQuery()
            ->getResult();
    }

    // ... (autres méthodes de votre repository, si nécessaire)
}
