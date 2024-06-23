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

    public function findBySearchCriteria(string $searchTerm, array $materials = [], $prixMin = null, $prixMax = null, array $categories = [], bool $enStock = false, string $sort = 'prix-asc'): array
    {
        $qb = $this->createQueryBuilder('p');

        // Recherche par terme
        if ($searchTerm !== null) {
            $qb->andWhere('p.Nom LIKE :searchTerm OR p.Description LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Filtre par matériaux (CORRIGÉ)
        if (!empty($materiaux)) {
            $qb->join('p.materials', 'm') // Jointure avec la table product_materiaux
                ->andWhere('m.id IN (:materials)')
                ->setParameter('materials', $materials);
        }

        // Filtre par prix
        if ($prixMin !== null) {
            $qb->andWhere('p.prix >= :prixMin')
                ->setParameter('prixMin', $prixMin);
        }
        if ($prixMax !== null) {
            $qb->andWhere('p.prix <= :prixMax')
                ->setParameter('prixMax', $prixMax);
        }

        // Filtre par catégories
        if (!empty($categories)) {
            $qb->andWhere('p.category IN (:categories)')
                ->setParameter('categories', $categories);
        }

        // Filtre par disponibilité
        if ($enStock) {
            $qb->andWhere('p.Stock > 0');
        }

        // Tri
        switch ($sort) {
            case 'prix-asc':
                $qb->orderBy('p.prix', 'ASC');
                break;
            case 'prix-desc':
                $qb->orderBy('p.prix', 'DESC');
                break;
            // Ajoutez d'autres cas de tri si nécessaire
        }
        
        return $qb->getQuery()->getResult();
    }
}
