<?php

namespace App\Repository;

use App\Entity\Takeknowledge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Takeknowledge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Takeknowledge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Takeknowledge[]    findAll()
 * @method Takeknowledge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TakeknowledgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Takeknowledge::class);
    }

    // /**
    //  * @return Takeknowledge[] Returns an array of Takeknowledge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Takeknowledge
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
