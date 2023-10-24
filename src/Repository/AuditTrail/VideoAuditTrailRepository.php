<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\VideoAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoAuditTrail[]    findAll()
 * @method VideoAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoAuditTrail::class);
    }
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


    // /**
    //  * @return VideoAuditTrail[] Returns an array of VideoAuditTrail objects
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
    public function findOneBySomeField($value): ?VideoAuditTrail
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
