<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\TeamMTHAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamMTHAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamMTHAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamMTHAuditTrail[]    findAll()
 * @method TeamMTHAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamMTHAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamMTHAuditTrail::class);
    }

    // /**
    //  * @return TeamMTHAuditTrail[] Returns an array of TeamMTHAuditTrail objects
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
    public function findOneBySomeField($value): ?TeamMTHAuditTrail
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findEntitiesByDate($str){
        return $this->createQueryBuilder('v')
            ->where('v.date LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }

    public function findEntitiesByType($str){
        return $this->createQueryBuilder('v')
            ->where('v.modif_type LIKE :str')
            ->setParameter('str', $str)
            ->getQuery()
            ->getResult();
    }
}
