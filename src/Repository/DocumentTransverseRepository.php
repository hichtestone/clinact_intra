<?php

namespace App\Repository;

use App\Entity\DocumentTransverse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentTransverse|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentTransverse|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentTransverse[]    findAll()
 * @method DocumentTransverse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTransverseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentTransverse::class);
    }


    /**
     *  @return DocumentTransverse[] Returns an array of DocumentTransverse objects
     */

    public function findGenaralDocuments()
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.documentType','t')
            ->Where('t.name = :val')
            ->setParameter('val', 1)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     *  @return DocumentTransverse[] Returns an array of DocumentTransverse objects
     */
    public function findQualiteDocuments()
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.documentType','t')
            ->Where('t.name = :val')
            ->setParameter('val', 2)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?DocumentTransverse
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
