<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\DocumentTransverseAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentTransverseAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentTransverseAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentTransverseAuditTrail[]    findAll()
 * @method DocumentTransverseAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTransverseAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentTransverseAuditTrail::class);
    }

    // /**
    //  * @return DocumentTransverseAuditTrail[] Returns an array of DocumentTransverseAuditTrail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentTransverseAuditTrail
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
