<?php

namespace App\Repository;

use App\Entity\OrderDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les détails de commande.
 */
class OrderDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetail::class);
    }

    /**
     * Récupère les détails d'une commande par son ID.
     *
     * @param int $orderId L'ID de la commande.
     * @return OrderDetail[]
     */

    // public function findByOrderId(int $orderId): array
    // {
    //     return $this->createQueryBuilder('od')
    //         ->join('od.order', 'o')  // Jointure avec la table Order
    //         ->andWhere('o.order_id = :orderId')  // On filtre sur order_id de la table Order
    //         ->setParameter('orderId', $orderId)
    //         ->getQuery()
    //         ->getResult();
    // }
    // public function findByOrderId(int $orderId): array
    // {
    //     return $this->createQueryBuilder('od') // 'od' représente OrderDetail
    //         ->where('od.order = :orderId')     // Jointure implicite grâce à la relation
    //         ->setParameter('orderId', $orderId)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findByOrderId(int $orderId): array
    // {
    //     return $this->createQueryBuilder('od')
    //         ->select('od', 'p')  // Sélectionner OrderDetail (od) et Produit (p)
    //         ->join('od.product', 'p') // Jointure avec la table Produits
    //         ->andWhere('od.order = :orderId')
    //         ->setParameter('orderId', $orderId)
    //         ->getQuery()
    //         ->getResult();
    // }
    public function findByOrderId(int $orderId): array
    {
        return $this->createQueryBuilder('od')
            ->select('od', 'p', 'pp.photoUrl') // Sélectionne l'URL de la photo principale
            ->join('od.product', 'p')
            ->leftJoin('p.productPhotos', 'pp', 'WITH', 'pp.isPrimary = true')
            ->andWhere('od.order = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult();
    }



    // Nouvelle méthode : récupérer un détail par son ID
    // public function find($id, $lockMode = null, $lockVersion = null): ?OrderDetail
    // {
    //     return $this->createQueryBuilder('od')
    //         ->andWhere('od.orderDetailId = :id')
    //         ->setParameter('id', $id)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
    public function find($id, $lockMode = null, $lockVersion = null): ?OrderDetail
    {
        return $this->findOneBy(['orderDetailId' => $id]);
    }
}
