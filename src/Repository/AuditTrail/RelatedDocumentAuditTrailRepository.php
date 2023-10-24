<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\RelatedDocumentAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelatedDocumentAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelatedDocumentAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelatedDocumentAuditTrail[]    findAll()
 * @method RelatedDocumentAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelatedDocumentAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelatedDocumentAuditTrail::class);
    }

    // /**
    //  * @return RelatedDocumentAuditTrail[] Returns an array of RelatedDocumentAuditTrail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RelatedDocumentAuditTrail
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
