<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\VersionDocumentTransverseAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionDocumentTransverseAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionDocumentTransverseAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionDocumentTransverseAuditTrail[]    findAll()
 * @method VersionDocumentTransverseAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionDocumentTransverseAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionDocumentTransverseAuditTrail::class);
    }

    // /**
    //  * @return VersionDocumentTransverseAuditTrail[] Returns an array of VersionDocumentTransverseAuditTrail objects
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
    public function findOneBySomeField($value): ?VersionDocumentTransverseAuditTrail
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
