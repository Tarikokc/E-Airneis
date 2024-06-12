<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    /**
     * @return Produits[] Returns an array of Produits objects with their photos
     */
    public function findAllWithPhotos()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productPhotos', 'photo') // Corrigé: Jointure avec les photos
            ->addSelect('photo') // Ajouté: Sélectionne les photos
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produits[] Returns an array of Produits objects filtered by search term
     */
    public function findBySearchTerm(?string $searchTerm): array
    {
        $qb = $this->createQueryBuilder('p');

        if ($searchTerm) {
            $qb->andWhere('p.Nom LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
