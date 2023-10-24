<?php

namespace App\Repository;

use App\Entity\TeamMTH;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamMTH|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamMTH|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamMTH[]    findAll()
 * @method TeamMTH[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamMTHRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamMTH::class);
    }

    /**
     * @return @return TeamMTH[] Returns an array of TeamMTH objects
     */
    public function findlasts()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.publishedAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return TeamMTH[] Returns an array of TeamMTH objects
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
    public function findOneBySomeField($value): ?TeamMTH
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findTeamOrdred()
    {
        return $this->createQueryBuilder('v')
            ->where('v.deletedAt IS  NULL ')
            ->orderBy('v.id', 'DESC')
            ->orderBy('v.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findEntitiesByCriteria($str){
        return $this->createQueryBuilder('v')
            ->Where('v.title LIKE :str')
            ->orWhere('v.description LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }

    public function findEntitiesByDate($str){
        return $this->createQueryBuilder('v')
            ->where('v.publishedAt LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }
}
