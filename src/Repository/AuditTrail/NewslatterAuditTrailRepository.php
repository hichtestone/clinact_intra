<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\NewslatterAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewslatterAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewslatterAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewslatterAuditTrail[]    findAll()
 * @method NewslatterAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewslatterAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewslatterAuditTrail::class);
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
    //  * @return NewslatterAuditTrail[] Returns an array of NewslatterAuditTrail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewslatterAuditTrail
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
