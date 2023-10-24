<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\DocumentTypeAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentTypeAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentTypeAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentTypeAuditTrail[]    findAll()
 * @method DocumentTypeAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTypeAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentTypeAuditTrail::class);
    }

    // /**
    //  * @return DocumentTypeAuditTrail[] Returns an array of DocumentTypeAuditTrail objects
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
    public function findOneBySomeField($value): ?DocumentTypeAuditTrail
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
