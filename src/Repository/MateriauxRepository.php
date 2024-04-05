<?php

namespace App\Repository;

use App\Entity\Materiaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiaux>
 *
 * @method Materiaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiaux[]    findAll()
 * @method Materiaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MateriauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiaux::class);
    }

//    /**
//     * @return Materiaux[] Returns an array of Materiaux objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Materiaux
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
