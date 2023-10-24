<?php

namespace App\Repository;

use App\Entity\Newslatter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Newslatter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Newslatter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Newslatter[]    findAll()
 * @method Newslatter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewslatterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newslatter::class);
    }

    public function findLastOne(): ?Newslatter
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findEntitiesByTitle($str){
        return $this->createQueryBuilder('v')
            ->Where('v.title LIKE :str')
            ->orWhere('v.description LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }


    public function findEntitiesByDate($str){
        return $this->createQueryBuilder('n')
            ->where('n.createdAt LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Newslatter[] Returns an array of Newslatter objects
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
    public function findOneBySomeField($value): ?Newslatter
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
