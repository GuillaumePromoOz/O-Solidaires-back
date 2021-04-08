<?php

namespace App\Repository;

use App\Entity\Proposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Proposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proposition[]    findAll()
 * @method Proposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposition::class);
    }



    /**
     * Find all propositions by category
     * 
     */
    public function findAllByCategory($category)
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Proposition p
            WHERE p.category = :category '
        )
            ->setParameter('category', $category);

        return $query->getResult();
    }

    /**
     * Find all propositions ordered by date DESC 
     * 
     */
    public function findAllOrderedByIdDesc()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Proposition p
            ORDER BY p.id DESC'
        );

        return $query->getResult();
    }


    // /**
    //  * @return Proposition[] Returns an array of Proposition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Proposition
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
