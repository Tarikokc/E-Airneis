<?php

namespace App\Repository;

use App\Entity\Designer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Designer>
 */
class DesignerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Designer::class);
    }

    public function findByName(string $name): ?Designer
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.designerName = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllWithProducts(): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.products', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();
    }
}
