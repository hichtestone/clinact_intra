<?php

namespace App\Repository;

use App\Entity\PresedentWord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PresedentWord|null find($id, $lockMode = null, $lockVersion = null)
 * @method PresedentWord|null findOneBy(array $criteria, array $orderBy = null)
 * @method PresedentWord[]    findAll()
 * @method PresedentWord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresedentWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PresedentWord::class);
    }
    public function findLastWord()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findEntitiesByString($str){
        return $this->createQueryBuilder('w')
            ->where('w.object LIKE :str')
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
    //  * @return PresedentWord[] Returns an array of PresedentWord objects
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
    public function findOneBySomeField($value): ?PresedentWord
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
