<?php

namespace App\Repository\AuditTrail;

use App\Entity\AuditTrail\PresedentWordAuditTrail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PresedentWordAuditTrail|null find($id, $lockMode = null, $lockVersion = null)
 * @method PresedentWordAuditTrail|null findOneBy(array $criteria, array $orderBy = null)
 * @method PresedentWordAuditTrail[]    findAll()
 * @method PresedentWordAuditTrail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresedentWordAuditTrailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PresedentWordAuditTrail::class);
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
    //  * @return PresedentWordAuditTrail[] Returns an array of PresedentWordAuditTrail objects
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
    public function findOneBySomeField($value): ?PresedentWordAuditTrail
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
