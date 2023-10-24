<?php

namespace App\Repository;

use App\Entity\VersionDocumentTransverse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionDocumentTransverse|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionDocumentTransverse|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionDocumentTransverse[]    findAll()
 * @method VersionDocumentTransverse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionDocumentTransverseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionDocumentTransverse::class);
    }

    // /**
    //  * @return VersionDocumentTransverse[] Returns an array of VersionDocumentTransverse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VersionDocumentTransverse
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
