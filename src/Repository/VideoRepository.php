<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function findLastOne()
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.id', 'DESC')
            ->orderBy('v.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findVideoOrdred()
    {
        return $this->createQueryBuilder('v')
            ->where('v.deletedAt IS  NULL ')
            ->orderBy('v.id', 'DESC')
            ->orderBy('v.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findEntitiesByString($str){
        return $this->createQueryBuilder('v')
            ->innerJoin('v.tags', 't')
            ->where('t.name LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }

    public function findEntitiesByTitle($str){
        return $this->createQueryBuilder('v')
            ->innerJoin('v.tags', 't')
            ->Where('v.title LIKE :str')
            ->orWhere('t.name LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }

    public function findEntitiesByDate($str){
        return $this->createQueryBuilder('v')
            ->where('v.createdAt LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Video[] Returns an array of Video objects
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
    public function findOneBySomeField($value): ?Video
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
