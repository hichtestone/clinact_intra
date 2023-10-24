<?php

namespace App\Repository;

use App\Entity\RelatedDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelatedDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelatedDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelatedDocument[]    findAll()
 * @method RelatedDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelatedDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelatedDocument::class);
    }

    // /**
    //  * @return RelatedDocument[] Returns an array of RelatedDocument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RelatedDocument
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
