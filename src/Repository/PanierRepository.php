<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    // public function findPanierWithProductDetails(int $userId)
    // {
    //     return $this->createQueryBuilder('p')
    //         ->select('p', 'product.productId', 'product.Nom', 'product.Description', 'product.prix', 'product.Stock', 'product.image') // Ajout de product.image
    //         ->leftJoin('p.produit', 'product')
    //         ->andWhere('p.user = :userId')
    //         ->setParameter('userId', $userId)
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findPanierWithProductDetails(int $userId)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'product.productId', 'product.Nom', 'product.Description', 'product.prix', 'product.Stock', 'photo.photoUrl') // Sélectionne photo.photoUrl
            ->leftJoin('p.produit', 'product')
            ->leftJoin('product.productPhotos', 'photo')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }









    //    /**
    //     * @return Panier[] Returns an array of Panier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Panier
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
