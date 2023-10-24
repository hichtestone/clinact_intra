<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\ProfileAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfileAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileAuditTrail[]    findAll()
 * @method ProfileAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileAuditTrail::class);
    }

    // /**
    //  * @return ProfileAuditTrail[] Returns an array of ProfileAuditTrail objects
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
    public function findOneBySomeField($value): ?ProfileAuditTrail
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
